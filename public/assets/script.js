let selectedFiles = [];
let isProcessing = false;
const FileLimt = 4;

document.addEventListener("DOMContentLoaded", function () {
    const uploadZone = document.getElementById("upload-zone");
    const fileInput = document.getElementById("file-input");
    const selectedFilesDiv = document.getElementById("selected-files");
    const filesList = document.getElementById("files-list");
    const submitBtn = document.getElementById("submit-btn");
    const merchantSelect = document.getElementById("merchant-select");

    // Click to upload
    uploadZone.addEventListener("click", function () {
        if (!isProcessing) {
            fileInput.click();
        }
    });

    // File input change
    fileInput.addEventListener("change", function () {
        if (!isProcessing && this.files.length > 0) {
            handleFiles(Array.from(this.files));
        }
    });

    // Drag events
    uploadZone.addEventListener("dragover", function (e) {
        e.preventDefault();
        this.classList.add("dragover");
    });

    uploadZone.addEventListener("dragleave", function (e) {
        e.preventDefault();
        this.classList.remove("dragover");
    });

    uploadZone.addEventListener("drop", function (e) {
        e.preventDefault();
        this.classList.remove("dragover");

        if (!isProcessing && e.dataTransfer.files.length > 0) {
            handleFiles(Array.from(e.dataTransfer.files));
        }
    });

    // Handle files
    function handleFiles(files) {
        if (isProcessing) return;

        const validFiles = files.filter((file) => {
            return (
                file.type === "application/pdf" ||
                file.type === "text/csv" ||
                file.name.toLowerCase().endsWith(".pdf") ||
                file.name.toLowerCase().endsWith(".csv")
            );
        });

        if(validFiles.length > FileLimt){
            showNotification(
                `maximum ${FileLimt} is allowed`,
                'error'
            );

            return;
        }

        if (validFiles.length === 0) {
            showNotification(
                "Please select valid PDF or CSV files only!",
                "error"
            );
            return;
        }

        // Add new files
        validFiles.forEach((file) => {
            const exists = selectedFiles.some(
                (f) => f.name === file.name && f.size === file.size
            );
            if (!exists) {
                selectedFiles.push(file);
            }
        });

        displayFiles();
        updateSubmitButton();
        showNotification(`${validFiles.length} file(s) added successfully!`);
    }

    // Display files
    function displayFiles() {
        if (selectedFiles.length === 0) {
            selectedFilesDiv.classList.remove("show");
            return;
        }

        selectedFilesDiv.classList.add("show");
        filesList.innerHTML = "";

        selectedFiles.forEach((file, index) => {
            const isPdf =
                file.type === "application/pdf" ||
                file.name.toLowerCase().endsWith(".pdf");
            const fileType = isPdf ? "pdf" : "csv";
            const fileIcon = isPdf ? "fa-file-pdf" : "fa-file-csv";
            const fileSize = formatFileSize(file.size);

            const fileItem = document.createElement("div");
            fileItem.className = "file-item";
            fileItem.style.animationDelay = index * 0.1 + "s";

            fileItem.innerHTML = `
                        <div class="file-info">
                            <div class="file-icon ${fileType}">
                                <i class="fas ${fileIcon}"></i>
                            </div>
                            <div class="file-details">
                                <h4>${escapeHtml(file.name)}</h4>
                                <p>${fileSize}</p>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="file-actions">
                            <button type="button" class="btn-remove" onclick="removeFile(${index})">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    `;

            filesList.appendChild(fileItem);
        });
    }

    // Remove file function (global scope)
    window.removeFile = function (index) {
        if (isProcessing || index < 0 || index >= selectedFiles.length) return;

        selectedFiles.splice(index, 1);
        displayFiles();
        updateSubmitButton();
        showNotification("File removed successfully!");
    };

    // Update submit button
    function updateSubmitButton() {
        const merchantSelected = merchantSelect.value !== "";
        const filesSelected = selectedFiles.length > 0;
        submitBtn.disabled = !(merchantSelected && filesSelected);
    }

    // Merchant change
    merchantSelect.addEventListener("change", updateSubmitButton);

    // Form submit
    document
        .getElementById("upload-form")
        .addEventListener("submit", function (e) {
            e.preventDefault();

            if (isProcessing) return;

            if (selectedFiles.length === 0) {
                showNotification("Please select at least one file!", "error");
                return;
            }

            if (merchantSelect.value === "") {
                showNotification("Please select a merchant!", "error");
                return;
            }

            simulateUpload();
        });

    // Simulate upload
    function simulateUpload() {
        if (isProcessing) return;

        isProcessing = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin"></i> Uploading...';

        const progressBars = document.querySelectorAll(".progress-fill");
        let progress = 0;

        const interval = setInterval(() => {
            progress += Math.random() * 10 + 5;
            if (progress > 100) progress = 100;

            progressBars.forEach((bar) => {
                bar.style.width = progress + "%";
            });

            if (progress >= 100) {
                clearInterval(interval);
                setTimeout(() => {
                    showNotification("Files uploaded successfully!");
                    resetForm();
                }, 500);
            }
        }, 150);
    }

    // Reset form
    function resetForm() {
        isProcessing = false;
        selectedFiles = [];
        fileInput.value = "";
        merchantSelect.value = "";
        selectedFilesDiv.classList.remove("show");
        filesList.innerHTML = "";
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Files';
    }

    // Show notification
    function showNotification(message, type = "success") {
        const notification = document.getElementById("notification");
        const notificationText = document.getElementById("notification-text");

        notification.className = "notification";
        if (type === "error") {
            notification.classList.add("error");
        }

        notificationText.textContent = message;
        notification.classList.add("show");

        setTimeout(() => {
            notification.classList.remove("show");
        }, 3000);
    }

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    // Initialize
    updateSubmitButton();
});
