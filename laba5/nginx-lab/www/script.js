// Таймер для напоминания
let inactivityTimer;

function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
        alert("Пожалуйста, заполните форму! Осталось мало времени.");
        document.querySelectorAll('input, select').forEach(field => {
            field.style.border = '2px solid orange';
            field.style.transition = 'border 0.3s';
        });
    }, 15000);
}

document.addEventListener('keydown', resetInactivityTimer);
document.addEventListener('mousemove', resetInactivityTimer);
resetInactivityTimer();

// Только alert перед отправкой
document.getElementById("conferenceForm").addEventListener("submit", function(e) {
    const name = document.querySelector("[name='name']").value;
    const birthYear = document.querySelector("[name='birthYear']").value;
    const sectionSelect = document.querySelector("[name='section']");
    const sectionText = sectionSelect.options[sectionSelect.selectedIndex].text;
    const certificate = document.querySelector("[name='certificate']").checked ? "Да" : "Нет";
    
    let participation = "Онлайн";
    if(document.querySelector("[name='participation']:checked")) {
        participation = document.querySelector("[name='participation']:checked").value === "online" ? "Онлайн" : "Очно";
    }
    
    alert("Регистрация на конференцию:\n\n" +
          "Имя: " + name + "\n" +
          "Год рождения: " + birthYear + "\n" +
          "Секция: " + sectionText + "\n" +
          "Сертификат: " + certificate + "\n" +
          "Форма участия: " + participation);
    
    resetInactivityTimer();
});