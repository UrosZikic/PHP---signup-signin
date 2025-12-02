const upload_trigger = document.querySelector("#upload_trigger");
const upload_modal = document.querySelector(".upload_modal");
const quit_modal = document.querySelector(".quit_modal");
const file = document.querySelector("#file");
const submit_file_image = document.querySelector("#submit_file_image");

upload_trigger.addEventListener("click", () => {
  upload_modal.classList.remove("remove_upload_modal");
});

quit_modal.addEventListener("click", () => {
  upload_modal.classList.add("remove_upload_modal");
  console.log(file.value);
});

file.addEventListener("change", () => {
  submit_file_image.disabled = false;
});
