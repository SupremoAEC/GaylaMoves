// app.js

const menu = document.querySelector('#mobile-menu');
const menuLinks = document.querySelector('.navbar__menu');
const destinationCards = document.querySelectorAll('.destination-card');
const modal = document.getElementById('destinationModal');

menu.addEventListener('click', function () {
  menu.classList.toggle('is-active');
  menuLinks.classList.toggle('active');
});

destinationCards.forEach(function (card) {
  card.addEventListener('click', function () {
    const title = card.querySelector('.card-subtitle').textContent.trim();
    const subtitle = card.querySelector('.card-title').textContent.trim();
    const description = "Description for " + title;
    openModal(title, subtitle, description);
  });
});

function openModal(title, subtitle, description) {
  document.getElementById('modalTitle').innerHTML = title + ' - ' + subtitle;
  document.getElementById('modalDescription').innerHTML = description;
  modal.style.display = 'block';
}

function closeModal() {
  modal.style.display = 'none';
}

// Close the modal if the user clicks outside the modal content
window.onclick = function (event) {
  if (event.target == modal) {
    closeModal();
  }
};
