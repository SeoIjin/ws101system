<?php
session_start();
// require admin session
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: sign-in.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
  <title>Modern Admin Dashboard</title>
  <link rel="stylesheet" href="design.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    /* Header actions: right-align Request/Logout in the page header */
    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }
    .header-actions {
      display: flex;
      gap: 0.5rem;
      align-items: center;
    }
    /* Use same .tab visual style if present, but make header buttons slightly smaller */
    .header-actions .tab {
      padding: 6px 10px;
      font-size: 0.95rem;
      cursor: pointer;
    }
    .header-actions .logout-tab {
      background: transparent;
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 6px;
    }
  </style>
</head>
<body class="body">
    <main>
      <div class="page-header">
        <img src="logo.jfif" alt="seal">
        <h1>Admin Dashboard</h1>
        <div class="header-actions">
          <button class="tab" data-status="request" id="requestTab">Request</button>
          <button class="tab logout-tab" id="logoutTab">Logout</button>
        </div>
      </div>
    <div class="main-content">
      <!--analytics numbers only-->
      <div class="analytics">
        <div class="card-container">
        <div class="card">
          <h2 id="totalCount" style="color: #2E5DFC;"></h2>
          <small>Total</small>
        </div>
        </div>
        <div class="card">
          <h2 id="reviewCount" style="color: #F66D31;"></h2>
          <small>Under Review</small>
        </div>
        <div class="card">
          <h2 id="progressCount" style="color: #E27508;"></h2>
          <small>In Progress</small>
        </div>
        <div class="card">
          <h2 id="readyCount" style="color: #505B6D;"></h2>
          <small>Ready</small>
        </div>
        <div class="card">
          <h2 id="completedCount" style="color: #07A840;"></h2>
          <small>Completed</small>
        </div>
      </div>
      <!-- Container for search and table -->
  <div class="boxed">
    <h1 style="font-family: 'Poppins'; font-weight: 300;">Request Management</h1>
    <p style="font-family: 'Poppins'; font-weight: 100;">Manage and track all health request from citizens</p>
<div class="table-container">
  <div class="search-bar">
    <i class="fa fa-search"></i>
    <input type="text" id="searchInput" placeholder="Search by ID, name, or request type... ">
  </div>
<!--tabs-->
<div class="tabs">
  <button class="tab active" data-status="all">All</button>
  <button class="tab" data-status="new">New</button>
  <button class="tab" data-status="review">Review</button>
  <button class="tab" data-status="progress">Progress</button>
  <button class="tab" data-status="ready">Ready</button>
  <button class="tab" data-status="done">Done</button>
</div>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="requestTableBody"></tbody>
  </table>
</div>
</div>
    </main>
  </div>
  <script>
  // Keep track of selected tab
  let currentFilter = "all";

  // Fetch requests from server API
  async function fetchRequests() {
    try {
      const res = await fetch('api_get_requests.php', {cache: 'no-store'});
      if (!res.ok) {
        console.error('Failed to fetch requests', res.status);
        return [];
      }
      const data = await res.json();
      return Array.isArray(data) ? data : [];
    } catch (err) {
      console.error('Error fetching requests', err);
      return [];
    }
  }

  // Load, filter and render requests
  async function loadRequests() {
    const tableBody = document.getElementById("requestTableBody");
    const requests = await fetchRequests();
    const searchInput = document.getElementById("searchInput")?.value.toLowerCase() || "";

    // Apply search filter
    let filteredRequests = requests.filter(r =>
      ('' + (r.id || '')).toLowerCase().includes(searchInput) ||
      (r.name || '').toLowerCase().includes(searchInput) ||
      (r.type || '').toLowerCase().includes(searchInput)
    );

    // Apply tab (status) filter
    if (currentFilter !== "all") {
      filteredRequests = filteredRequests.filter(r => {
        const status = (r.status || '').toLowerCase();
        if (currentFilter === "review") return status === "under review" || status === "review";
        if (currentFilter === "progress") return status === "in progress" || status === "progress";
        if (currentFilter === "ready") return status === "ready";
        if (currentFilter === "done") return status === "completed" || status === "done";
        if (currentFilter === "new") return status === "new";
        return true;
      });
    }

    // Populate table
    tableBody.innerHTML = "";
    if (filteredRequests.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="7" class="center">No matching requests</td></tr>`;
    } else {
      filteredRequests.forEach(r => {
        const priority = (r.priority || 'Medium').toLowerCase();
        const priorityClass =
          priority === "low" ? "priority-low" : priority === "medium" ? "priority-medium" : "priority-high";

        const st = (r.status || '').toLowerCase();
        const statusClass = st === "under review" ? "status-under-review" : st === "in progress" ? "status-in-progress" : st === "ready" ? "status-ready" : "status-completed";

        tableBody.innerHTML += `
          <tr>
            <td>${r.id}</td>
            <td>${r.name}</td>
            <td>${r.type}</td>
            <td class="${priorityClass}">${r.priority || 'Medium'}</td>
            <td class="${statusClass}">${r.status || 'New'}</td>
            <td>${r.submitted}</td>
            <td class="actions">
              <a href="ReqDet&Upd.php?id=${encodeURIComponent(r.id)}"><i class="fa fa-eye"></i></a>
              <a href="UpdReqAdmin.php?id=${encodeURIComponent(r.id)}"><i class="fa fa-edit"></i></a>
            </td>
          </tr>`;
      });
    }
    // Update analytics dashboard based on full requests set
    updateDashboard(requests);
  }
  // Dashboard analytics update
  function updateDashboard(reqs) {
    const counts = {
      total: reqs.length,
      review: reqs.filter(r => r.status.toLowerCase() === "under review").length,
      progress: reqs.filter(r => r.status.toLowerCase() === "in progress").length,
      ready: reqs.filter(r => r.status.toLowerCase() === "ready").length,
      completed: reqs.filter(r => r.status.toLowerCase() === "completed").length,
    };
    document.getElementById("totalCount").textContent = counts.total;
    document.getElementById("reviewCount").textContent = counts.review;
    document.getElementById("progressCount").textContent = counts.progress;
    document.getElementById("readyCount").textContent = counts.ready;
    document.getElementById("completedCount").textContent = counts.completed;
  }
  // Tabs — filter table when clicked
  document.querySelectorAll(".tab").forEach(tab => {
    tab.addEventListener("click", () => {
      document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
      tab.classList.add("active");
      currentFilter = tab.dataset.status;
      loadRequests();
    });
  });
  // Search listener
  document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    if (searchInput) searchInput.addEventListener("input", loadRequests);
    loadRequests();
  });
  // Optional: Auto-refresh table
  setInterval(loadRequests, 3000);
  // Request and Logout handlers
  (function() {
    const requestTabEl = document.getElementById('requestTab');
    if (requestTabEl) {
      requestTabEl.addEventListener('click', (e) => {
        // Navigate to the detailed request page
        window.location.href = 'ReqDet&Upd.php';
      });
    }
    const logoutTabEl = document.getElementById('logoutTab');
    if (logoutTabEl) {
      logoutTabEl.addEventListener('click', (e) => {
        // Clear client-side session-like data and redirect to sign-in
        try { localStorage.clear(); } catch (err) { /* ignore */ }
        window.location.href = 'sign-in.php';
      });
    }
  })();
</script>
</body>
</html>