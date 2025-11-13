<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Request Details & Updates</title>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");
    @import url("https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css");
    @import url("https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-straight/css/uicons-solid-straight.css");
    @import url("https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-straight/css/uicons-thin-straight.css");


    :root {
      --primary: #228650;
      --light-bg: #deffec;
      --white: #ffffff;
      --border: #d6dad9;
      --highlight: #b6ffec;
      --shadow: rgba(0, 0, 0, 0.1);
    }


    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }


    body {
      background-color: var(--light-bg);
      min-height: 100vh;
      color: #333;
      line-height: 1.5;
    }


    .page-header {
      display: flex;
      align-items: center;
      background-color: var(--white);
      padding: 1em 2em;
      box-shadow: 0 2px 8px var(--shadow);
      gap: 1.5em;
    }


    .page-header img {
      height: 5rem;
      object-fit: contain;
    }


    .page-heading {
      color: var(--primary);
      font-size: 2rem;
      font-weight: 600;
    }


    main.container {
      position: relative;
      display: grid;
      grid-template-columns: 0.5fr 0.4fr 0.3fr;
      grid-template-rows: 0.25fr repeat(4, 1fr);
      gap: 1rem;
      width: 95vw;
      max-width: 1500px;
      margin: 2rem auto;
      padding: 3rem;
      background-color: var(--white);
      border: 2px solid #228650;
      border-radius: 1rem;
      box-shadow: 0 4px 15px var(--shadow);
      overflow: hidden;
      z-index: 1;
    }


    .container-bg-overlay,
    .client-det-bg-overlay,
    .desc-bg-overlay,
    .update-hist-bg-overlay {
      position: absolute;
      top: 1.5rem;
      left: 1.5rem;
      right: 1.5rem;
      bottom: 1.5rem;
      background-image: url("./assets/logo/barangay-logo.svg");
      background-position: center center;
      background-repeat: no-repeat;
      background-size: 60%;
      opacity: 0.01;
      pointer-events: none;
      border-radius: 1rem;
      z-index: 0;
    }


    .cont-heading {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      padding: 1.25rem 1.5rem;
      background-color: var(--highlight);
      border-radius: 0.75rem 0.75rem 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: inset 0 -3px 8px rgba(0, 0, 0, 0.05);
      z-index: 10;
    }


    .cont-heading h3 {
      color: var(--primary);
      font-weight: 700;
      font-size: 1.5rem;
    }


    .req-heading-status {
      font-weight: 600;
      color: var(--primary);
      background-color: #d7f4e3;
      padding: 0.4rem 1rem;
      border: 2px #149b92 solid;
      border-radius: 9999px;
    }


    .req-heading-status p {
      color: #149b92;
    }


    section {
      background-color: var(--white);
      border: 2px solid var(--border);
      border-radius: 1rem;
      padding: 1.25rem 1.5rem;
      opacity: 0.95;
      z-index: 1;
      box-shadow: 0 1px 6px var(--shadow);
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }


    section h3 {
      color: var(--primary);
      font-weight: 600;
      font-size: 1.25rem;
    }


    i,
    h4 {
      color: #228650;
    }


    .verified-res,
    .verified-num,
    .verified-email {
      color: #00b050;
    }


    .desc-card {
      background-color: #e8f9f0;
      padding: 1rem;
      border: 2px solid #b5dfc9;
      border-radius: 1rem;
      display: flex;
      flex-direction: column;
      align-content: center;
      gap: 0.75rem;
      z-index: 1;
    }


    .desc-card p {
      font-size: 0.8rem;
    }


    .submit-since {
      text-align: right;
    }


    .Rx {
      display: inline-block;
    }


    .invoice-image1,
    .invoice-image2 {
      width: 6rem;
      margin-right: 0.75rem;
      border: 1px solid #b5dfc9;
      object-fit: contain;
      box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.5);
    }


    .status-entry {
      background-color: #ffffff;
      border-left: 6px solid #3498db;
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 16px;
    }


    .status-entry.completed,
    .status-entry.in-progress,
    .status-entry.reviewed,
    .status-entry.submitted {
      border: 2px #00b050 solid;
    }


    .status-entry h3 {
      margin: 0 0 8px;
      font-size: 1.2em;
      color: #2c3e50;
    }


    .status-entry .timestamp {
      font-size: 0.9em;
      color: #7f8c8d;
      margin-bottom: 8px;
    }


    .status-entry p {
      margin: 4px 0;
      font-size: 0.95em;
      color: #555;
    }


    .summary-label {
      font-weight: bold;
      color: #34495e;
      display: block;
      margin-bottom: 4px;
    }


    .summary-value {
      color: #2c3e50;
      font-size: 1em;
    }


    .assigned {
      font-size: 0.95em;
      color: #7f8c8d;
      margin-top: 4px;
    }


    .client-details {
      grid-area: 2 / 1 / 4 / 2;
    }


    .description {
      grid-area: 4 / 1 / 6 / 2;
    }


    .update-history {
      grid-area: 2 / 2 / 6 / 3;
    }


    .req-prio {
      grid-area: 2 / 3 / 3 / 4;
      background-color: #f9fefb;
      border: 2px solid var(--border);
      border-radius: 1rem;
      padding: 1.25rem 1.5rem;
      box-shadow: 0 1px 6px var(--shadow);
      display: flex;
      flex-direction: column;
      gap: 0.6rem;
      /* small even space between the two groups */
    }


    .assigned-to {
      grid-area: 3 / 3 / 4 / 4;
      /* directly below req-prio */
      background-color: #f9fefb;
      border: 2px solid var(--border);
      border-radius: 1rem;
      padding: 1.25rem 1.5rem;
      box-shadow: 0 1px 6px var(--shadow);
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
    }


    .req-type,
    .prio-level {
      display: flex;
      flex-direction: column;
      gap: 0.3rem;
      /* keeps internal spacing consistent */
    }


    .req-prio h3 {
      color: var(--primary);
      font-size: 1.1rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.4rem;
      margin: 0;
    }


    .request-type {
      background-color: #dcfce7;
      padding: 0.6rem 2rem 0.8rem 0.8rem;
      border-radius: 0.75rem;
      font-weight: 600;
      color: #1a4731;
      width: fit-content;
    }


    /* Priority dropdown */
    #priority-level {
      text-align: center;
      width: 50%;
      border-radius: 0.75rem;
      padding: 0.5rem 0.75rem;
      font-size: 0.9rem;
      background-color: #ffe7b3;
      border: 1px solid #e0c97a;
      color: #7a5a00;
      transition: background-color 0.3s ease, border-color 0.3s ease;
    }


    /* Priority color themes */
    #priority-level.low {
      background-color: #d4f8e8 !important;
      color: #006b3c !important;
      border-color: #64c394 !important;
    }


    #priority-level.medium {
      background-color: #fff3b0 !important;
      color: #9a7800 !important;
      border-color: #e0c97a !important;
    }


    #priority-level.high {
      background-color: #ffd1d1 !important;
      color: #a30000 !important;
      border-color: #d36a6a !important;
    }


    .completed-on {
      position: absolute;
      bottom: 1rem;
      /* brings it back inside the container */
      right: 2rem;
      font-size: 0.95rem;
      color: var(--primary);
      display: flex;
      align-items: center;
      gap: 0.4rem;
      z-index: 10;
    }


    .completed-on i {
      color: var(--primary);
      font-size: 1rem;
    }


    .completed-on span {
      color: var(--primary);
    }


    .req-status-wrapper {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      /* space between box and icon */
    }


    .req-status-wrapper i {
      color: #149b92;
      font-size: 1.3rem;
    }

    /* Embedded update form (small card inside Assigned to) */
    .embedded-update { margin-top: 1rem; display: flex; flex-direction: column; gap: 0.5rem; }
    .btn-update {
      display: inline-block;
      background: #228650;
      color: #fff;
      border: 0;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }
    .embedded-panel {
      margin-top: 0.5rem;
      background:#f9fffa;
      border:1px solid rgba(34,134,80,0.12);
      padding:12px;
      border-radius:10px;
      box-shadow:0 6px 14px rgba(34,134,80,0.04);
    }
    .embedded-panel label{ display:block; font-weight:700; margin:8px 0 6px; color:#11623f }
    .embedded-panel select, .embedded-panel textarea{ width:100%; padding:8px; border-radius:8px; border:1px solid #dfeee0; background:#fff }
    .embedded-panel .btn-embedded-submit{ margin-top:10px; background:#11623f; color:#fff; padding:8px 12px; border-radius:8px; border:0; cursor:pointer; font-weight:700 }


    footer {
      text-align: center;
    }


    @media (max-width: 1024px) {
      main.container {
        display: flex;
        flex-direction: column;
        padding-top: 7.5rem;
        width: 90vw;
      }


      section {
        margin-bottom: 1rem;
      }
    }


    @media (max-width: 600px) {
      main.container {
        padding-top: 7.5rem;
      }


      .history-panel {
        padding: 16px;
      }


      .history-header h2 {
        font-size: 1.4em;
      }
    }


    @media (max-width: 400px) {
      main.container {
        padding-top: 12rem;
      }
    }
  </style>
</head>


<body>
  <header class="page-header">
   <img src="logo.jfif" alt="Barangay 170 Logo" alt="seal">
    <div class="page-heading">Request Details & Updates</div>
  </header>

  <main class="container">
    <div class="container-bg-overlay"></div>


    <div class="cont-heading">
      <div class="req-heading-details">
        <h3>Request Details</h3>
        <p>Ticket ID: BHR-2024-001234</p>
      </div>
      <div class="req-status-wrapper">
        <div class="req-heading-status">
          <p>Completed</p>
        </div>
        <i class="fi fi-ss-check"></i>
      </div>
    </div>


    </div>


    <section class="client-details">
      <div class="client-det-bg-overlay"></div>
      <h4><i class="fi fi-sr-user-trust"></i> Citizen Information</h4>
      <p>Juan Dela Cruz
        <span class="verified-res">Barangay Resident <i class="fi fi-ss-check"></i></span>
      </p>
      <h4><i class="fi fi-sr-phone-flip"></i> Contact Number</h4>
      <p>0912-345-6790 <span class="verified-num">Verified Number <i class="fi fi-ss-check"></i></span></p>
      <h4><i class="fi fi-sr-envelope"></i> Email</h4>
      <p>juandelacruz01@gmail.com <span class="verified-email">Verified Email <i class="fi fi-ss-check"></i></span></p>
      <h4><i class="fi fi-ss-house-chimney"></i> Home Address</h4>
      <p>#001 Sesame St. Brgy. 170, Caloocan City</p>
      <h4><i class="fi fi-ss-id-badge"></i> Citizen ID</h4>
      <p>BHR-2012-0234</p>
    </section>


    <section class="description">
      <div class="desc-bg-overlay"></div>
      <h3>Description</h3>
      <div class="desc-card">
        <p>Need assistance with chronic medication for hypertension. Unable to afford current
          prescription costs.</p>
        <div class="Rx">
          <div>
            <img
              src="https://76badc9293637386fe97468efea3d407.cdn.bubble.io/cdn-cgi/image/w=1536,h=1536,f=auto,dpr=1,fit=contain/f1642239256505x227252233618472580/default_invoice_1.png"
              alt="Prescription Invoice" class="invoice-image1">
            <img
              src="https://76badc9293637386fe97468efea3d407.cdn.bubble.io/cdn-cgi/image/w=1536,h=1536,f=auto,dpr=1,fit=contain/f1642239256505x227252233618472580/default_invoice_1.png"
              alt="Prescription Invoice" class="invoice-image2">
          </div>
        </div>
        <p class="submit-since">Submitted since: December 12, 2024 at 9:45 PM</p>
      </div>
    </section>


    <section class="update-history">
      <div class="update-hist-bg-overlay"></div>
      <div class="history-header">
        <h3>Update History</h3>
        <p>Track all status changes and updates</p>
      </div>
      <!-- Completed -->
      <div class="status-entry completed">
        <h3 style="color: #228650;"><i style="color: #228650;" class="fi fi-ss-bullet"></i>Completed</h3>
        <div class="timestamp" style="color: #228650;">Dec 14, 2024 – 12:28 PM</div>
        <p>Citizen notified via email.</p>
        <p style="color: #228650;">Updated by: Admin</p>
      </div>


      <!-- In Progress -->
      <div class="status-entry in-progress">
        <h3 style="color: #c29e64;"><i style="color: #c29e64;" class="fi fi-ss-bullet"></i>In Progress</h3>
        <div class="timestamp" style="color: #228650;">Dec 14, 2024 – 9:08 AM</div>
        <p>Team dispatched to verify medical documents and expenses.</p>
        <p style="color: #228650;">Updated by: Admin</p>
      </div>


      <!-- Reviewed -->
      <div class="status-entry reviewed">
        <h3 style="color: #adc66b;"><i style="color: #adc66b;" class="fi fi-ss-bullet"></i>Reviewed</h3>
        <div class="timestamp" style="color: #228650;">Dec 14, 2024 – 7:45 AM</div>
        <p>Request reviewed by an Admin.</p>
        <p style="color: #228650;">Updated by: Admin</p>
      </div>


      <!-- Submitted -->
      <div class="status-entry submitted">
        <h3><i style="color: #8a8985;" class="fi fi-ss-bullet"></i>Submitted</h3>
        <div class="timestamp" style="color: #228650;">Dec 13, 2024 – 9:45 PM</div>
        <p>Request has been received and will undergo review.</p>
        <p style="color: #228650;">Updated by: System</p>
      </div>
    </section>


    <section class="req-prio">
      <div class="req-type">
        <h3><i class="fi fi-ss-clipboard-list"></i></i> Request Type</h3>
        <p class="request-type">Medical Assistance</p>
      </div>


      <div class="prio-level">
        <h3><i class="fi fi-sr-light-emergency-on"></i> Priority Level</h3>
        <select name="priority-dropdown" id="priority-level">
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
      </div>
    </section>


    <section class="assigned-to">
      <h3>Assigned to:</h3>
      <span class="summary-value">Maria de Clara (Team A)</span>
      <div class="assigned" style="color: #228650;">Dec 14, 2024 – 9:08 AM</div>
      
      <!-- Embedded Update (UpdReqAdmin) inserted here -->
      <div class="embedded-update">
        <button id="toggleUpdateBtn" class="btn-update" aria-expanded="false">Update Request</button>

        <div id="embeddedUpdatePanel" class="embedded-panel" hidden>
          <form id="embeddedUpdateForm" autocomplete="off">
            <label for="embedded-status">New Status</label>
            <select id="embedded-status" name="status" aria-label="New status">
              <option>Completed</option>
              <option>Pending</option>
              <option>In progress</option>
              <option>Rejected</option>
            </select>

            <label for="embedded-message">Update Message</label>
            <textarea id="embedded-message" name="message" placeholder="Please provide details about this update, actions taken, or next steps..." rows="4"></textarea>

            <button class="btn-embedded-submit" type="submit" aria-label="Update request">Save Update</button>
          </form>
        </div>
      </div>

      <a href="#" class="location-link" style="color: #228650;">View shared location &amp; map here</a>
    </section>
    <p class="completed-on">
      <i class="fi fi-ss-check"></i> Completed on:
      <span>December 14, 2024 – 12:28 PM</span>
    </p>
  </main>


  <footer>
    Uicons by <a href="https://www.flaticon.com/uicons" target="_blank">Flaticon</a>
  </footer>
  <script>
    const prioritySelect = document.getElementById("priority-level");


    prioritySelect.addEventListener("change", function () {
      this.classList.remove("low", "medium", "high");
      const selectedText = this.options[this.selectedIndex].text.toLowerCase();
      if (selectedText.includes("low")) this.classList.add("low");
      else if (selectedText.includes("medium")) this.classList.add("medium");
      else if (selectedText.includes("high")) this.classList.add("high");
    });

    // Embedded update toggle + submit handler
    (function(){
      const toggleBtn = document.getElementById('toggleUpdateBtn');
      const panel = document.getElementById('embeddedUpdatePanel');
      const form = document.getElementById('embeddedUpdateForm');
      if (toggleBtn && panel) {
        toggleBtn.addEventListener('click', function(){
          const isHidden = panel.hasAttribute('hidden');
          if (isHidden) {
            panel.removeAttribute('hidden');
            toggleBtn.setAttribute('aria-expanded','true');
            toggleBtn.textContent = 'Close Update';
          } else {
            panel.setAttribute('hidden','');
            toggleBtn.setAttribute('aria-expanded','false');
            toggleBtn.textContent = 'Update Request';
          }
        });
      }

      if (form) {
        form.addEventListener('submit', function(e){
          e.preventDefault();
          const status = document.getElementById('embedded-status').value;
          const message = document.getElementById('embedded-message').value.trim();
          const now = new Date();
          const ts = now.toLocaleString();

          // create a new status-entry block and insert at top of .update-history
          const history = document.querySelector('.update-history');
          if (history) {
            const entry = document.createElement('div');
            entry.className = 'status-entry';
            entry.innerHTML = `\n              <h3 style="color:#228650;"><i style="color:#228650;" class="fi fi-ss-bullet"></i>${status}</h3>\n              <div class="timestamp" style="color:#228650;">${ts}</div>\n              <p>${message ? message : 'No message provided.'}</p>\n              <p style="color:#228650;">Updated by: Admin</p>\n            `;
            // insert as first status entry
            history.insertBefore(entry, history.firstChild.nextSibling || history.firstChild);
          }

          // close panel and clear fields
          panel.setAttribute('hidden','');
          toggleBtn.setAttribute('aria-expanded','false');
          toggleBtn.textContent = 'Update Request';
          form.reset();
          alert('Update saved (demo)');
        });
      }
    })();
  </script>
</body>


</html>