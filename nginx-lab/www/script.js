// Таймер для напоминания (штрафное задание)
let inactivityTimer;

// Функция сброса таймера
function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
        alert("⏰ Пожалуйста, заполните форму! Осталось мало времени.");
        document.querySelectorAll('input, select').forEach(field => {
            field.style.border = '2px solid orange';
            field.style.transition = 'border 0.3s';
        });
    }, 15000); // 15 секунд
}

// Отслеживаем активность пользователя
document.addEventListener('keydown', resetInactivityTimer);
document.addEventListener('mousemove', resetInactivityTimer);

// Запускаем таймер при загрузке
resetInactivityTimer();

// Обработка отправки формы
document.getElementById("conferenceForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Отменяем перезагрузку страницы
    
    // Собираем данные из формы
    const formData = new FormData(this);
    
    // Формируем вывод
    let output = "<h3>📋 Данные регистрации:</h3>";
    
    for (const [key, value] of formData.entries()) {
        // Преобразуем ключи в читаемый вид
        let label = key;
        if (key === 'name') label = 'Имя';
        if (key === 'birthYear') label = 'Год рождения';
        if (key === 'section') label = 'Секция';
        if (key === 'certificate') label = 'Сертификат';
        if (key === 'participation') label = 'Форма участия';
        
        // Для чекбокса показываем "Да" вместо "on"
        let displayValue = value;
        if (key === 'certificate' && value === 'on') displayValue = '✅ Да';
        
        output += `<p><strong>${label}:</strong> ${displayValue}</p>`;
    }
    
    // Выводим результат
    document.getElementById("result").innerHTML = output;
    
    // Сбрасываем таймер после отправки
    resetInactivityTimer();
    
    // Небольшая анимация успеха
    document.getElementById("result").style.backgroundColor = "#e8f5e8";
    setTimeout(() => {
        document.getElementById("result").style.backgroundColor = "";
    }, 1000);
});