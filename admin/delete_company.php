<?php
include '../includes/db_connect.php';
if (isset($_POST['id'])) {
  $id = (int) $_POST['id'];
  mysqli_query($con, "DELETE FROM companies WHERE company_id = $id");
}
