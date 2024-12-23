// Function to initialize photo modal functionality
function setupPhotoModal(selector, modalPhotoId) {
  document.querySelectorAll(selector).forEach((img) => {
    img.addEventListener("click", function () {
      const photoSrc = this.getAttribute("data-photo");
      document.getElementById(modalPhotoId).setAttribute("src", photoSrc);
    });
  });
}

// Function to initialize Select2 dropdown
function setupSelect2(selector, placeholder) {
  $(selector).select2({
    placeholder: placeholder,
    allowClear: true,
  });
}

// Function to initialize file input with preview functionality
function setupPhotoInput(config) {
  const {
    inputId,
    previewId,
    fileNameId,
    clearButtonId,
    placeholderImage,
  } = config;

  const fileInput = document.getElementById(inputId);
  const previewImage = document.getElementById(previewId);
  const fileName = document.getElementById(fileNameId);
  const clearButton = document.getElementById(clearButtonId);

  if (!fileInput || !fileInput) {
    return;
  }

  fileInput.addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImage.innerHTML = `<img src="${e.target.result}" alt="Preview" />`;
        fileName.textContent = file.name;
        clearButton.classList.remove("d-none");
      };
      reader.readAsDataURL(file);
    }
  });

  clearButton.addEventListener("click", function () {
    fileInput.value = "";
    previewImage.innerHTML = `
      <span class="d-block text-center mb-2">
        <img src="${placeholderImage}" alt="Add" style="width: 50px" />
      </span>`;
    fileName.textContent = "";
    clearButton.classList.add("d-none");
  });
}

// Main initialization function
function initializeApp() {
  setupPhotoModal(".inventory-photo", "modalPhoto");
  setupSelect2("#inventory", "Choose inventory items");

  const photoInputConfigs = [
    {
      inputId: "coverPhoto",
      previewId: "coverPhotoPreview",
      fileNameId: "coverPhotoName",
      clearButtonId: "coverPhotoClear",
      placeholderImage: "/images/add.png",
    },
    {
      inputId: "travelPathPhoto",
      previewId: "travelPathPreview",
      fileNameId: "travelPathName",
      clearButtonId: "travelPathClear",
      placeholderImage: "/images/add.png",
    },
  ];

  photoInputConfigs.forEach(setupPhotoInput);

}



// Initialize the app when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", initializeApp);