const error_bubble = document.querySelector(".error_bubble");
const remove_bubble = setTimeout(() => {
  if (error_bubble) error_bubble.classList.add("remove_bubble");
}, 2000);

const load_bubble = document.querySelectorAll(".load_bubble");
let i = 0;
const load_in = setInterval(() => {
  load_bubble[i].classList.add("load_in");
  i++;
  if (i > load_bubble.length - 1) {
    clearInterval(load_in);
  }
}, 500);
