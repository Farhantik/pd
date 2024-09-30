document.getElementById("orderForm").onsubmit = function (event) {
  event.preventDefault(); // Mencegah pengiriman form default

  const customerName = document.getElementById("customerName").value;
  const dishSelection = document.querySelector(".dish-selection").value;
  const extras = Array.from(document.querySelectorAll(".extra:checked")).map(el => el.value);
  const quantity = document.querySelector(".quantity").value;
  const dateOfPurchase = document.getElementById("dateOfPurchase").value;

  // Mengirim data ke server
  fetch('submit_order.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ customerName, dishSelection, extras, quantity, dateOfPurchase }),
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Order submitted successfully!');
        // Reset form atau lakukan hal lain
      } else {
        alert('Error submitting order: ' + data.message);
      }
    })
    .catch((error) => {
      console.error('Error:', error);
    });
};

document.getElementById("orderForm").onsubmit = function (event) {
  event.preventDefault(); // Mencegah pengiriman form default

  const formData = new FormData(this);

  fetch('submit_order.php', {
    method: 'POST',
    body: formData,
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Order submitted successfully!');
        // Reset form atau lakukan hal lain
        document.getElementById("orderForm").reset();
      } else {
        alert('Error submitting order: ' + data.message);
      }
    })
    .catch((error) => {
      console.error('Error:', error);
    });
};