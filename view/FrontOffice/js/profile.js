// js/profile.js - Image preview for profile pic & banner
document.querySelectorAll('.file-input').forEach(input => {
    input.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => {
            const targetId = this.name === 'profile_pic' ? 'profilePic' : 'bannerImg';
            document.getElementById(targetId).src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
});