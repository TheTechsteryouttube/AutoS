<html>
<head>
  <link rel="stylesheet" href="../assets/css/company_dashboard.css?v=3">
</head>
<body>
<?php
include '../includes/db_connect.php';

$result = mysqli_query($con, "SELECT * FROM companies ORDER BY company_id ASC");
?>

<div class="container">
  <h2>Company List</h2>
  <button class="add-btn" onclick="document.getElementById('addCompanyForm').style.display='flex'">➕ Add Company</button>

  <table class="company-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Company Name</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $row['company_id'] ?></td>
          <td><?= htmlspecialchars($row['company_name']) ?></td>
          <td>
            <button class="edit-btn" onclick="editCompany(<?= $row['company_id'] ?>, '<?= htmlspecialchars($row['company_name']) ?>')">✏️ Edit</button>
            <button class="delete-btn" onclick="deleteCompany(<?= $row['company_id'] ?>)">🗑 Delete</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Add Company Modal -->
<div id="addCompanyForm" class="modal">
  <div class="modal-content">
    <h3>Add Company</h3>
    <input type="text" id="newCompanyName" placeholder="Company Name" />
    <div class="modal-actions">
      <button class="save-btn" onclick="submitNewCompany()">Save</button>
      <button class="cancel-btn" onclick="document.getElementById('addCompanyForm').style.display='none'">Cancel</button>
    </div>
  </div>
</div>

<!-- Edit Company Modal -->
<div id="editCompanyForm" class="modal">
  <div class="modal-content">
    <h3>Edit Company</h3>
    <input type="hidden" id="editCompanyId" />
    <input type="text" id="editCompanyName" />
    <div class="modal-actions">
      <button class="save-btn" onclick="submitEditCompany()">Update</button>
      <button class="cancel-btn" onclick="document.getElementById('editCompanyForm').style.display='none'">Cancel</button>
    </div>
  </div>
</div>

</body>
</html>
