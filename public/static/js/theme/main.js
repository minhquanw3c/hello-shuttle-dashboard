// Sidebar Toggle
var el = document.getElementById("wrapper");
var toggleButton = document.getElementById("menu-toggle");

toggleButton.onclick = function () {
  el.classList.toggle("toggled");
};

// Sidebar Dropdown toggle
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".sidebar .nav-link").forEach(function (element) {
    element.addEventListener("click", function (e) {    
      let nextEl = element.nextElementSibling;   
      let parentEl = element.parentElement;      
      if (nextEl) {
        e.preventDefault();
        let mycollapse = new bootstrap.Collapse(nextEl);
       
        if (nextEl.classList.contains("show")) {
          mycollapse.hide();        
        } else {        
          mycollapse.show();
          // find other submenus with class=show
          var opened_submenu =
            parentEl.parentElement.querySelector(".submenu.show");
          // if it exists, then close all of them
          if (opened_submenu) {
            new bootstrap.Collapse(opened_submenu);
          }
        }
      }
      if(nextEl.classList.contains("show")) {
        element.classList.add('opened');
      }else{
        element.classList.remove('opened');
      }
    });
  });
});

// Dark mode toggle
const checkbox = document.getElementById("checkbox");

const logo = document.getElementById("logo");

let darkmode = localStorage.getItem('dark');

if(darkmode) {
  document.body.classList.add('dark')
  logo.src = "assets/img/logo-dark.png";
}

// Get Current Date
const currentDate = new Date();
const formattedDate = currentDate.toLocaleDateString("en-US", {
  month: "short",
  day: "numeric",
  year: "numeric",
});
const dateSpan = document.getElementById("date")
if(dateSpan){
  dateSpan.innerHTML = formattedDate;
}

// $(document).ready(function() {
//   $('.select').niceSelect();
// });
const cartItem = document.querySelectorAll('.close');
cartItem.forEach(function(item){
  item.addEventListener('click', function(){
    let singleItem = item.parentElement
    let parent = singleItem.parentElement
    parent.removeChild(singleItem)   
  })
})


