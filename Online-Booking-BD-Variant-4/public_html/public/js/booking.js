document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('instrument_id');
    const teacherSelect = document.getElementById('teacher_id');
    const dateInput = document.getElementById('lesson_date');
    const slotsContainer = document.getElementById('slots-container');
    const durationInput = document.getElementById('duration_minutes');
    
    // 🔹 Загрузка преподавателей при выборе инструмента
    serviceSelect?.addEventListener('change', function() {
        const instrumentId = this.value;
        if (!instrumentId) return;
        
        fetch(`index.php?entity=teacher&action=ajax_list&instrument_id=${instrumentId}`)
            .then(r => r.json())
            .then(data => {
                teacherSelect.innerHTML = '<option value="">Выберите преподавателя</option>';
                data.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.teacher_id;
                    opt.textContent = `${t.last_name} ${t.first_name}`;
                    opt.dataset.duration = t.default_duration || 60;
                    teacherSelect.appendChild(opt);
                });
                durationInput.value = teacherSelect.options[1]?.dataset.duration || 60;
            });
    });
    
    // 🔹 Загрузка слотов при выборе даты + преподавателя
    function loadSlots() {
        const teacherId = teacherSelect.value;
        const date = dateInput.value;
        const duration = durationInput.value;
        
        if (!teacherId || !date) return;
        
        slotsContainer.innerHTML = '<div class="spinner">Загрузка...</div>';
        
        fetch(`api/get_slots.php?teacher_id=${teacherId}&date=${date}&duration=${duration}`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.slots.length > 0) {
                    slotsContainer.innerHTML = data.slots.map(slot => 
                        `<button type="button" class="slot-btn" data-datetime="${slot.datetime}">${slot.time}</button>`
                    ).join('');
                    
                    // Выбор слота
                    document.querySelectorAll('.slot-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('active'));
                            this.classList.add('active');
                            document.getElementById('lesson_datetime').value = this.dataset.datetime;
                        });
                    });
                } else {
                    slotsContainer.innerHTML = '<p class="text-muted">Нет свободного времени на эту дату</p>';
                }
            })
            .catch(() => {
                slotsContainer.innerHTML = '<p class="text-danger">Ошибка загрузки слотов</p>';
            });
    }
    
    teacherSelect?.addEventListener('change', loadSlots);
    dateInput?.addEventListener('change', loadSlots);
    durationInput?.addEventListener('change', loadSlots);
    
    // 🔹 AJAX-отправка формы с повторной проверкой слота
    const bookingForm = document.getElementById('booking-form');
    bookingForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Бронирование...';
        
        fetch('index.php?entity=appointment&action=create', {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('✅ Запись создана! Код бронирования: ' + data.booking_code);
                window.location.href = `index.php?entity=appointment&action=view&id=${data.appointment_id}`;
            } else {
                alert('❌ ' + data.error);
                // Перезагрузить слоты, если время занято
                if (data.error.includes('время') || data.error.includes('занято')) {
                    loadSlots();
                }
            }
        })
        .catch(() => alert('Ошибка соединения'))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Записаться';
        });
    });
});