<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Patient Medication Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/medical_rec.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            height: 90%;
            max-width: 1200px;
            max-height: 800px;
            overflow: auto;
            position: relative;
        }

        #imageModal {
            z-index: 9999;
            background: rgba(0,0,0,0.8);
        }

        #imageModalContent {
            width: 95%;
            height: 95%;
            padding: 40px 20px 20px 20px;
            display: flex;
            flex-direction: column;
        }

        #imageModalContent img {
            width: 100%;
            height: 100%;
            border: none;
            object-fit: contain;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 30px;
            cursor: pointer;
            color: #333;
            transition: color 0.2s;
            z-index: 10000;
            font-weight: bold;
        }

        .close:hover {
            color: #007bff;
        }

        .view-document {
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
        }

        .view-document:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <?php $this->renderComponent('navbar', $active); ?>

        <!-- Main Content -->
        <div class="main-content">
            <?php
            $pageTitle = "Medical Records";
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/header.php';
            ?>

            <div class="dashboard-content">
                <?php if (!empty($data['recordData'])): ?>
                        <div style="display: flex; justify-content: space-between; gap: 30px; margin-top: 2%; margin-bottom: 3%">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label for="dr-name">Doctor's Name:</label>
                                <p style="font-weight: bold;width: 20vw"><?= htmlspecialchars($data['recordData'][0]->doctor_name) ?></p>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label for="date">Date:</label>
                                <p style="font-weight: bold;width: 20vw"><?= htmlspecialchars($data['recordData'][0]->date) ?></p>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label for="diagnosis">Diagnosis:</label>
                                <p style="font-weight: bold;width:20vw"><?= htmlspecialchars($data['recordData'][0]->diagnosis) ?></p>
                            </div>
                        </div>
                <?php else: ?>
                    <p>No request details available.</p>
                <?php endif; ?>

                <div id="view-document-container" style="margin-top: 10px; margin-bottom: 20px">
                    <?php if (!empty($data['recordData'][0]->file_name)): ?>
                        <span style="font-weight: bold;">Open Attached File: </span>
                        <a href="#" class="view-document" data-file="<?= htmlspecialchars(urlencode($data['recordData'][0]->file_name)) ?>">View Document</a>
                    <?php endif; ?>
                </div>

                <h2>MEDICINES NEED TO BE GIVEN:</h2>

                <?php if (!empty($data['recordData'])): ?>
                    <table class="medication-table">
                        <thead>
                            <tr>
                                <th>Name of the Medication</th>
                                <th>Dosage of the Medication</th>
                                <th colspan="4">Number taken at a time</th>
                                <th>Substitution</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Morning</th>
                                <th>Noon</th>
                                <th>Night</th>
                                <th>If Needed</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['recordData'] as $med):
                                $takenTimes = !empty($med->taken_time) ? preg_split('/\s+/', trim($med->taken_time)) : [];
                                $med->morning = $takenTimes[0] ?? '0';
                                $med->noon = $takenTimes[1] ?? '0';
                                $med->night = $takenTimes[2] ?? '0';
                                $med->if_needed = $takenTimes[3] ?? '0';
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($med->medication_name) ?></td>
                                    <td><?= htmlspecialchars($med->dosage) ?></td>
                                    <td><?= htmlspecialchars($med->morning) ?></td>
                                    <td><?= htmlspecialchars($med->noon) ?></td>
                                    <td><?= htmlspecialchars($med->night) ?></td>
                                    <td><?= htmlspecialchars($med->if_needed) ?></td>
                                    <td><?= isset($med->substitution) && $med->substitution == 1 ? 'Not Allowed' : 'Allowed' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No medication details available.</p>
                <?php endif; ?>

                <div class="remarks-section">
                    <h3>Remarks</h3>
                    <textarea id="additionalRemarks" readonly><?= !empty($data['recordData'][0]->remark) ? htmlspecialchars($data['recordData'][0]->remark) : 'No remarks available.' ?></textarea>
                </div>
                <div class="back-button-container">
                    <button onclick="history.back()">Back</button>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Modal for Image Display -->
    <div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); justify-content: center; align-items: center; z-index: 9999;">
        <div id="imageModalContent" style="background: #fff; max-width: 95%; max-height: 95%; overflow: auto; border-radius: 8px; position: relative;">
            <span id="closeImageModal" class="close" style="position: absolute; top: 15px; right: 25px; font-size: 30px; cursor: pointer; color: #333;">×</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle "View Document" link clicks
            document.querySelectorAll('.view-document').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const file = this.getAttribute('data-file');
                    const modal = document.getElementById('imageModal');
                    const container = document.getElementById('imageModalContent');

                    // Create image element
                    const img = document.createElement('img');
                    img.src = `/wellbe/public/assets/files/prescription_documents/${file}`;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'contain';

                    // Clear previous content and append new image
                    while (container.firstChild && container.firstChild !== document.getElementById('closeImageModal')) {
                        container.removeChild(container.firstChild);
                    }
                    container.appendChild(img);

                    modal.style.display = 'flex';
                });
            });

            // Close Image Modal
            const imageModal = document.getElementById('imageModal');
            const closeImageModal = document.getElementById('closeImageModal');

            closeImageModal.addEventListener('click', function() {
                console.log('Closing image modal');
                imageModal.style.display = 'none';
                const modalContent = document.getElementById('imageModalContent');
                const img = modalContent.querySelector('img');
                if (img) {
                    img.remove();
                }
            });

            window.addEventListener('click', function(event) {
                if (event.target === imageModal) {
                    console.log('Closing image modal (clicked outside)');
                    imageModal.style.display = 'none';
                    const modalContent = document.getElementById('imageModalContent');
                    const img = modalContent.querySelector('img');
                    if (img) {
                        img.remove();
                    }
                }
            });
        });
    </script>
</body>

</html>