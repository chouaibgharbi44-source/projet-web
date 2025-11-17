
const subjects = ["Mathématiques", "Programmation", "Examens"];
const subjectsList = document.querySelector(".subjects-list");

subjects.forEach(sub => {
    const li = document.createElement("li");
    li.className = "subject-item";
    li.textContent = sub;
    subjectsList.appendChild(li);
});



const userTypeSelect = document.getElementById("userType");
const userTypes = ["", "Étudiant", "Professeur", "Administrateur"];

userTypes.forEach(type => {
    const opt = document.createElement("option");
    opt.value = type;
    opt.textContent = type === "" ? "Sélectionner..." : type;
    userTypeSelect.appendChild(opt);
});



const yearSelect = document.getElementById("year");

for (let i = 1; i <= 5; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.textContent = `${i}ère année`;
    yearSelect.appendChild(option);
}


const yearGroup = document.getElementById("yearGroup");

userTypeSelect.addEventListener("change", () => {
    if (userTypeSelect.value === "Étudiant") {
        yearGroup.style.display = "block";
        yearSelect.required = true;
    } else {
        yearGroup.style.display = "none";
        yearSelect.required = false;
    }
});



function setupImagePreview(inputId) {
    const input = document.getElementById(inputId);
    const box = input.parentElement;
    const img = box.querySelector(".preview-img");
    const placeholder = box.querySelector(".upload-placeholder");

    input.addEventListener("change", () => {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.style.display = "block";
                placeholder.style.display = "none";
            };
            reader.readAsDataURL(file);
        }
    });
}

setupImagePreview("bannerInput");
setupImagePreview("profilePicInput");



document.getElementById("userForm").addEventListener("submit", e => {
    const fields = ["studentId", "firstName", "lastName", "email", "password", "profilePicInput"];

    for (let id of fields) {
        const field = document.getElementById(id);
        if (!field.value) {
            alert("Veuillez remplir tous les champs obligatoires.");
            e.preventDefault();
            return;
        }
    }
});
