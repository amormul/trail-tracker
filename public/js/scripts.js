document.querySelectorAll(".inventory-photo").forEach((img) => {
        img.addEventListener("click", function () {
          const photoSrc = this.getAttribute("data-photo");
          document.getElementById("modalPhoto").setAttribute("src", photoSrc);
        });
      });
      $(document).ready(function () {
        $("#inventory").select2({
          placeholder: "Choose inventory items",
          allowClear: true,
        });
      });
      document.addEventListener("DOMContentLoaded", function () {
        function setupPhotoInput(
          inputId,
          previewId,
          fileNameId,
          clearButtonId,
          placeholderImage
        ) {
          const fileInput = document.getElementById(inputId);
          const previewImage = document.getElementById(previewId);
          const fileName = document.getElementById(fileNameId);
          const clearButton = document.getElementById(clearButtonId);

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

        setupPhotoInput(
          "coverPhoto",
          "coverPhotoPreview",
          "coverPhotoName",
          "coverPhotoClear",
          "/images/add.png"
        );
        setupPhotoInput(
          "travelPathPhoto",
          "travelPathPreview",
          "travelPathName",
          "travelPathClear",
          "/images/add.png"
        );
      });
