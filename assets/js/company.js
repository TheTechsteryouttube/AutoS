// Utility to reload the company dashboard and highlight the sidebar link
function reloadCompanyDashboard() {
    const companyLink = document.querySelector(".sidebar a[onclick*='company_dashboard.php']");
    if (companyLink) {
      loadPage("company_dashboard.php", companyLink);
    } else {
      loadPage("company_dashboard.php"); // Fallback if sidebar link not found
    }
  }
  
  // Toggle the menu dropdown for edit/delete
  function toggleMenu(id) {
    document.querySelectorAll('.menu-content').forEach(menu => menu.style.display = 'none');
    const menuElement = document.getElementById('menu-' + id);
    if (menuElement) {
      menuElement.style.display = 'block';
    }
  }
  
  // Add a new company
  function submitNewCompany() {
    const name = document.getElementById("newCompanyName").value.trim();
  
    if (!name) {
      alert("Please enter a company name.");
      return;
    }
  
    fetch("../admin/add_company.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "company_name=" + encodeURIComponent(name)
    })
      .then(response => {
        if (!response.ok) throw new Error("Network response not ok");
        return response.text();
      })
      .then(res => {
        if (res.trim() == "success") {
          reloadCompanyDashboard();
        } else {
          alert("Failed to add company: " + res);
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("Failed to add company.");
      });
  }
  
  // Delete a company
  function deleteCompany(id) {
    if (confirm("Are you sure to delete this company?")) {
      fetch("../admin/delete_company.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + id
      })
        .then(res => res.text())
        .then(() => reloadCompanyDashboard())
        .catch(err => console.error("Delete error:", err));
    }
  }
  
  // Open the edit form
  function editCompany(id, name) {
    document.getElementById("editCompanyId").value = id;
    document.getElementById("editCompanyName").value = name;
    document.getElementById("editCompanyForm").style.display = 'block';
  }
  
  // Submit edit changes
  function submitEditCompany() {
    const id = document.getElementById("editCompanyId").value;
    const name = document.getElementById("editCompanyName").value.trim();
  
    if (!name) {
      alert("Please enter a company name.");
      return;
    }
  
    fetch("../admin/edit_company.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + id + "&name=" + encodeURIComponent(name)
    })
      .then(res => res.text())
      .then(() => reloadCompanyDashboard())
      .catch(err => console.error("Edit error:", err));
  }
  