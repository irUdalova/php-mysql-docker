import { autocomplete } from "./autocomplete.js";

let tags = [];
let tagsDB = application.tags;

// TODO - add input data to localstorage =====================

// window.addEventListener("load", function () {
//   tags = getLocalStorage() ?? [];
//   if (tags.length) {
//     addTags();
//   }
// });

// let wordData = {
//   word: "",
//   definion: "",
//   example: "",
//   tags: [],
// };

// let wordInput = document.getElementsByName("word")[0];
// let definInput = document.getElementsByName("definition")[0];
// let exampleInput = document.getElementsByName("example")[0];

// wordInput.addEventListener("input", function () {
//   localStorage.setItem("word", JSON.stringify(wordInput.value));
// });

// definInput.addEventListener("input", function () {
//   localStorage.setItem("definition", JSON.stringify(definInput.value));
// });

// exampleInput.addEventListener("input", function () {
//   localStorage.setItem("example", JSON.stringify(exampleInput.value));
// });

// ================add input tags=======================
const tagContainer = document.querySelector(".tags-input");

if (tagContainer) {
  const mainInput = document.createElement("input");

  const tagsCurrent = tagContainer.dataset.current;
  if (tagsCurrent) {
    tags = tagsCurrent.split(",");
    addTags();
  }

  mainInput.setAttribute("type", "text");
  mainInput.classList.add("main-input");
  mainInput.setAttribute("placeholder", "Add tag");
  tagContainer.appendChild(mainInput);

  mainInput.addEventListener("keydown", function (e) {
    if (e.key === "," || e.key === "Enter") {
      e.preventDefault();
      let enteredTag = mainInput.value;
      // console.log(enteredTag, "enteredTag");
      if (enteredTag && !tags.includes(enteredTag)) {
        let tagFilter = filterTag(enteredTag);
        if (tagFilter) {
          tags.push(filterTag(enteredTag));
          addTags();
        }
      }
      mainInput.value = "";
    }
  });

  mainInput.addEventListener("input", function (e) {
    let event = e.inputType ? "input" : "option";
    if (event === "option") {
      let enteredTag = e.target.value;
      if (enteredTag && !tags.includes(enteredTag)) {
        let tagFilter = filterTag(enteredTag);
        if (tagFilter) {
          tags.push(filterTag(enteredTag));
          addTags();
        }
      }
      mainInput.value = "";
    }
  });

  document.addEventListener("click", function (e) {
    if (e.target.tagName === "SPAN") {
      const value = e.target.getAttribute("data-item");
      const index = tags.indexOf(value);
      tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
      addTags();
    }
  });

  function reset() {
    document.querySelectorAll(".tag").forEach(function (tag) {
      tag.parentElement.removeChild(tag);
    });
  }

  function createTag(text) {
    const tag = document.createElement("div");
    tag.classList.add("tag");
    tag.textContent = text;

    const closeBtn = document.createElement("span");
    closeBtn.classList.add("material-symbols-outlined");
    closeBtn.innerText = "close";
    closeBtn.setAttribute("data-item", text);

    tag.appendChild(closeBtn);
    return tag;
  }

  function addTags() {
    reset();
    tags
      .slice()
      .reverse()
      .forEach(function (tag) {
        let tagEl = createTag(tag);
        tagContainer.prepend(tagEl);
      });
    // localStorage.setItem("tags", JSON.stringify(tags));
  }

  function filterTag(tag) {
    // return tag
    //   .replace(/[^\w -]/g, "")
    //   .trim()
    //   .replace(/\W+/g, "-");
    return tag
      .replace(/[^a-zа-яёЁЇїІіЄєҐґʼ]*/gi, "")
      .trim()
      .toLowerCase();
  }

  // function getLocalStorage() {
  //   let tagsLS = JSON.parse(localStorage.getItem("tags"));
  //   if (tagsLS) {
  //     return [...tagsLS];
  //   }
  // }

  let formSbmCreate = document.querySelector(".form-handle-word");

  formSbmCreate.addEventListener("submit", function () {
    if (tags.length) {
      tags.forEach(function (tag, index) {
        let hiddenInput = document.createElement("input");
        hiddenInput.setAttribute("type", "hidden");
        hiddenInput.setAttribute("name", `tags[]`);
        hiddenInput.value = tag;
        tagContainer.appendChild(hiddenInput);
      });
    }
    // localStorage.removeItem("tags");
  });

  //TODO - do something to don't lose user's data after reload

  // let wordInput = document.getElementsByName("word")[0];
  // let definInput = document.getElementsByName("definition")[0];
  // let exampleInput = document.getElementsByName("example")[0];

  // window.addEventListener("beforeunload", function (event) {
  //   if (
  //     tags.length ||
  //     wordInput.value ||
  //     definInput.value ||
  //     exampleInput.value
  //   ) {
  //     event.preventDefault();
  //     // Included for legacy support, e.g. Chrome/Edge < 119
  //     event.returnValue = true;
  //   }
  // });

  // ================add autocomplete=======================

  const datalist = document.createElement("datalist");
  datalist.setAttribute("id", "autocomplete-list");
  mainInput.setAttribute("list", "autocomplete-list");
  tagContainer.appendChild(datalist);

  autocomplete(tagsDB, datalist, "tag");
}
