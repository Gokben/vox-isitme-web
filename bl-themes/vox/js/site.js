(() => {
  const toggle = document.querySelector('.nav-toggle');
  const navigation = document.querySelector('.main-nav');
  if (toggle && navigation) {
    toggle.addEventListener('click', () => {
      const expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!expanded));
      navigation.classList.toggle('open', !expanded);
    });
  }

  const calendar = document.getElementById('calendar-days');
  if (!calendar) return;

  const monthNames = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
  const times = ['09:30', '10:20', '11:10', '13:30', '14:20', '15:10', '16:00', '16:50'];
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  let view = new Date(today.getFullYear(), today.getMonth(), 1);
  const dateInput = document.getElementById('appointment-date');
  const timeInput = document.getElementById('appointment-time');
  let selectedDate = dateInput.value ? new Date(`${dateInput.value}T00:00:00`) : null;
  let selectedTime = timeInput.value || '';
  const monthLabel = document.getElementById('calendar-month');
  const slotArea = document.getElementById('time-slots');
  const formatIso = date => `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;

  const renderSlots = () => {
    slotArea.innerHTML = '';
    if (!selectedDate) {
      slotArea.textContent = 'Önce uygun bir gün seçin.';
      return;
    }
    times.forEach(time => {
      const button = document.createElement('button');
      button.type = 'button';
      button.textContent = time;
      button.classList.toggle('selected', time === selectedTime);
      button.addEventListener('click', () => {
        selectedTime = time;
        timeInput.value = time;
        renderSlots();
      });
      slotArea.appendChild(button);
    });
  };

  const renderCalendar = () => {
    monthLabel.textContent = `${monthNames[view.getMonth()]} ${view.getFullYear()}`;
    calendar.innerHTML = '';
    const offset = (new Date(view.getFullYear(), view.getMonth(), 1).getDay() + 6) % 7;
    const days = new Date(view.getFullYear(), view.getMonth() + 1, 0).getDate();
    for (let i = 0; i < offset; i += 1) calendar.appendChild(document.createElement('span'));
    for (let day = 1; day <= days; day += 1) {
      const date = new Date(view.getFullYear(), view.getMonth(), day);
      const button = document.createElement('button');
      button.type = 'button';
      button.textContent = String(day);
      button.disabled = date < today || date.getDay() === 0;
      button.classList.toggle('selected', Boolean(selectedDate) && formatIso(date) === formatIso(selectedDate));
      button.addEventListener('click', () => {
        selectedDate = date;
        selectedTime = '';
        dateInput.value = formatIso(date);
        timeInput.value = '';
        renderCalendar();
        renderSlots();
      });
      calendar.appendChild(button);
    }
    document.getElementById('previous-month').disabled = view.getFullYear() === today.getFullYear() && view.getMonth() === today.getMonth();
  };

  document.getElementById('previous-month').addEventListener('click', () => { view.setMonth(view.getMonth() - 1); renderCalendar(); });
  document.getElementById('next-month').addEventListener('click', () => { view.setMonth(view.getMonth() + 1); renderCalendar(); });

  const updateClock = () => {
    const now = new Date();
    document.getElementById('live-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
  };
  updateClock();
  setInterval(updateClock, 30000);
  renderCalendar();
  renderSlots();
})();
