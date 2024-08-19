/* // You can use this file to add interactive features or fetch dynamic data
document.addEventListener('DOMContentLoaded', () => {
    // Example of dynamically updating widget data
    document.getElementById('Number of Employees').innerText = '150'; // You could fetch this data from an API
    document.getElementById('sales-today').innerText = '$450'; // You could fetch this data from an API
    document.getElementById('pending-orders').innerText = '10'; // You could fetch this data from an API
});
 */
function showEdit() {
    var x = document.getElementById("edit");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }