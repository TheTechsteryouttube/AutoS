<html>
    <head>
    <link rel="stylesheet" href="../assets/css/addModel.css">
    </head>
<div class="form-container">
  <h2>Add New Model</h2>
  <form action="insert_model.php" method="POST">
      
      <label for="model_name">Model Name:</label>
      <input type="text" id="model_name" name="model_name" required>
      
      <label for="company_id">Company:</label>
      <select id="company_id" name="company_id" required>
          <?php
          include '../includes/db_connect.php';
          $sql = "SELECT company_id, company_name FROM companies";
          $result = $con->query($sql);
          while ($row = $result->fetch_assoc()) {
              echo "<option value='{$row['company_id']}'>{$row['company_name']}</option>";
          }
          ?>
      </select>

      <button type="submit">Add Model</button>
  </form>
</div>
</html>
