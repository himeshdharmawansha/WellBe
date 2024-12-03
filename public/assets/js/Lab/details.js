const uploadedReports = {};

window.openReportPopup = function (patientId) {
   const patientIdElement = document.getElementById('patientId');
   if (patientIdElement) {
      patientIdElement.innerText = patientId;
   } else {
      console.error('Element with ID "patientId" not found.');
   }

   const modal = document.getElementById('reportPopup');
   if (modal) {
      modal.style.display = "block";
      loadReports(patientId);
   } else {
      console.error('Modal with ID "reportPopup" not found.');
   }
};

window.closeReportPopup = function () {
   const modal = document.getElementById('reportPopup');
   if (modal) {
      modal.style.display = "none";
   } else {
      console.error('Modal with ID "reportPopup" not found.');
   }
};

window.uploadFile = function () {
   const fileInput = document.createElement('input');
   fileInput.type = 'file';
   fileInput.onchange = function (event) {
      const file = event.target.files[0];
      if (file) {
         const patientId = document.getElementById('patientId').innerText;

         if (!patientId) {
            alert('Patient ID is missing.');
            return;
         }

         if (!uploadedReports[patientId]) {
            uploadedReports[patientId] = [];
         }

         const existingFileIndex = uploadedReports[patientId].findIndex(report => report.name === file.name);
         if (existingFileIndex !== -1) {
            uploadedReports[patientId][existingFileIndex] = {
               name: file.name,
               url: URL.createObjectURL(file),
               comments: uploadedReports[patientId][existingFileIndex].comments || ''
            };
         } else {
            uploadedReports[patientId].push({
               name: file.name,
               url: URL.createObjectURL(file),
               comments: ''
            });
         }

         loadReports(patientId);
      }
   };

   fileInput.click();
};

function loadReports(patientId) {
   const reportTableBody = document.getElementById('reportTableBody');
   reportTableBody.innerHTML = '';

   const reports = uploadedReports[patientId] || [];
   reports.forEach(report => {
      const row = document.createElement('tr');

      row.innerHTML = `
         <td class="file-info">
            <i class="fa-solid fa-file-pdf" style="margin-right: 8px;"></i>
            <a href="${report.url}" download="${report.name}">${report.name}</a>
         </td>
         <td class="delete-icon-cell">
            <i class="fa fa-trash delete-icon" onclick="removeReport('${patientId}', '${report.name}')"></i>
         </td>
      `;
      reportTableBody.appendChild(row);
   });
}




window.removeReport = function (patientId, fileName) {
   const reports = uploadedReports[patientId] || [];
   const index = reports.findIndex(report => report.name === fileName);

   if (index !== -1) {
      reports.splice(index, 1);
      loadReports(patientId);
   }
};

window.saveReports = function () {
   const patientId = document.getElementById('patientId').innerText;

   if (patientId && uploadedReports[patientId]) {
      console.log(`Reports for patient ${patientId}:`, uploadedReports[patientId]);
      closeReportPopup();
   } else {
      alert('No reports to save.');
   }
};
