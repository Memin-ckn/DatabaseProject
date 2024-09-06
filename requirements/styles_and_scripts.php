<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../style/styles.css">
<link rel="icon" type="image/x-icon" href="../requirements/sesa.png ">
<script src="https://kit.fontawesome.com/b96ee86dee.js" crossorigin="anonymous"></script>
<script>
    function showPsw(element) {
        var row = element.closest('tr'); // Find the closest row
        var input = row.querySelector('.userpsw'); // Get the input in this row
        var eyeIcon = row.querySelector('.eye'); // Get the eye icon in this row

        if (input.type === "password") {
            input.type = "text";
            eyeIcon.className = "eye fa-solid fa-eye-slash";
        } else {
            input.type = "password";
            eyeIcon.className = "eye fa-solid fa-eye";
        }
    }
</script>