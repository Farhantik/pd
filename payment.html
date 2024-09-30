<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #000000, #333333);
      color: #333;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 500px;
      margin: 100px auto;
      background-color: #313131c2;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      padding: 30px;
    }

    h2 {
      color: #fbf6f6;
      text-align: center;
      margin-bottom: 20px;
      font-size: 2rem;
      font-weight: 600;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      color: #fff;
      font-size: 1.2em;
      margin-bottom: 10px;
    }

    input[type="text"],
    input[type="number"] {
      padding: 10px;
      margin-bottom: 20px;
      border: none;
      border-radius: 8px;
      font-size: 1.1em;
    }

    .btn-group {
      display: flex;
      justify-content: space-between;
    }

    .btn-pay,
    .btn-cancel {
      padding: 12px;
      font-size: 1.1em;
      border-radius: 8px;
      color: white;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
      text-transform: uppercase;
      width: 48%;
    }

    .btn-pay {
      background-color: #28a745;
    }

    .btn-pay:hover {
      background-color: #218838;
    }

    .btn-cancel {
      background-color: #dc3545;
    }

    .btn-cancel:hover {
      background-color: #c82333;
    }

    table {
      width: 100%;
      margin-top: 20px;
    }

    th,
    td {
      color: #fff;
      padding: 12px;
      text-align: left;
      font-size: 1.1em;
    }

    th {
      background-color: #222;
    }

    td {
      background-color: #333;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Payment Page</h2>
    <form id="paymentForm">
      <label for="name">Customer Name</label>
      <input type="text" id="name" name="name" readonly>

      <label for="dish">Dishes Ordered</label>
      <table id="dishesTable">
        <thead>
          <tr>
            <th>Dish</th>
            <th>Extras</th>
            <th>Quantity</th>
          </tr>
        </thead>
        <tbody id="dishesBody"></tbody>
      </table>

      <label for="total">Total Price (Rp)</label>
      <input type="text" id="total" name="total" readonly>

      <label for="paymentMethod">Payment Method</label>
      <input type="text" id="paymentMethod" name="paymentMethod"
        placeholder="Enter Payment Method (e.g., Credit Card, Transfer)" required>

      <div class="btn-group">
        <button type="submit" class="btn-pay">Confirm Payment</button>
        <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
      </div>
    </form>
  </div>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const customerName = urlParams.get('name');
    const totalPrice = urlParams.get('total');
    const dishes = [];

    for (let param of urlParams.entries()) {
      if (param[0].startsWith('dish')) {
        const dishIndex = param[0].split('_')[1];
        const dishName = param[1];
        const quantity = urlParams.get(`quantity_${dishIndex}`);
        const extras = urlParams.get(`extras_${dishIndex}`);

        dishes.push({ name: dishName, quantity: quantity, extras: extras });
      }
    }

    document.getElementById('name').value = customerName;

    const dishesTableBody = document.getElementById('dishesBody');
    dishes.forEach(dish => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${dish.name}</td>
        <td>${dish.extras || 'None'}</td>
        <td>${dish.quantity}</td>
      `;
      dishesTableBody.appendChild(row);
    });

    document.getElementById('total').value = 'Rp ' + parseInt(totalPrice).toLocaleString();

    document.getElementById('paymentForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const paymentMethod = document.getElementById('paymentMethod').value;
      const paymentConfirmationUrl = `payment-confirmation.html?name=${customerName}&total=${totalPrice}&paymentMethod=${encodeURIComponent(paymentMethod)}`;
      window.location.href = paymentConfirmationUrl;
    });

    document.getElementById('cancelBtn').addEventListener('click', function () {
      const orderDetailsUrl = `customer.html?name=${customerName}&total=${totalPrice}`;
      window.location.href = orderDetailsUrl;
    });
  </script>
</body>

</html>