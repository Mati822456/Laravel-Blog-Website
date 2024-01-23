toggleHeight();

function toggleHeight() {
    let e = document.querySelector('.filtr_body');
    let button_collapse = document.querySelector(".button_collapse");

    if (e.style.height !== '0px') {
        e.style.height = '0px';
        button_collapse.classList.remove('fa-caret-up');
        button_collapse.classList.add('fa-caret-down');
    } else {
        e.style.height = 'auto';
        button_collapse.classList.remove('fa-caret-down');
        button_collapse.classList.add('fa-caret-up');
    }
}

document.querySelector('.filtr_collapse').addEventListener('click', function () {
    toggleHeight();
});

const searchTermInput = document.querySelector("input[name='term']");
const searchFormInput = document.querySelector("#term");

searchTermInput.addEventListener('keyup', function () {
    searchFormInput.value = searchTermInput.value;
});

window.radioCheck = function(number){
    for (let i = 1; i <= 4; i++) {
        if (number === i) {
            document.querySelector(".rec_" + i).classList.add("active");
            document.querySelector(".rec_" + i + " .dot").innerHTML = '<i class="fa-solid fa-square-xmark"></i>';
        } else {
            document.querySelector(".rec_" + i).classList.remove("active");
            document.querySelector(".rec_" + i + " .dot").innerHTML = '<i class="fa-regular fa-square"></i>';
        }
    }

    const limit = document.querySelector('#limit');

    switch (number) {
        case 1:
            limit.value = "20";
            break;
        case 2:
            limit.value = "50";
            break;
        case 3:
            limit.value = '100';
            break;
        case 4:
            limit.value = '0';
            break;
    }
}

const filter_buttons = document.querySelectorAll(".buttons.sort_buttons .filter-button");

window.filterCheck = function(number, order) {
    let i = 1;
    filter_buttons.forEach((button) => {
        if (i === number){
            button.classList.add("active");
            button.querySelector(".dot").innerHTML = '<i class="fa-solid fa-circle-check"></i>';
        } else {
            button.querySelector(".dot").innerHTML = '<i class="fa-solid fa-circle-dot"></i>';
            button.classList.remove("active");
        }
        i++;
    });
    document.querySelector('#order').value = order;
}

switch(document.querySelector('#order').value) {
    case 'desc':
        filterCheck(1, 'desc');
        break;
    case 'asc':
        filterCheck(2, 'asc');
        break;
    case 'ascAlphabetical':
        filterCheck(3, 'ascAlphabetical');
        break;
    case 'descAlphabetical':
        filterCheck(4, 'descAlphabetical');
        break;
    default:
        filterCheck(1, 'desc');
}

switch(parseInt(document.querySelector("#limit").value)){
    case 0:
        radioCheck(4);
        break;
    case 20:
        radioCheck(1);
        break;
    case 50:
        radioCheck(2);
        break;
    case 100:
        radioCheck(3);
        break;
}

document.querySelector('.show_results').addEventListener('click', function () {
    document.getElementById('filter_form').submit();
});

window.changeView = function(name, mode) {
    let elements = null;

    if (mode === 'post') {
        elements = document.querySelectorAll(".posts-list .post");
        localStorage.setItem('postView', name);
    } else {
        elements = document.querySelectorAll(".comments-list .comment");
        localStorage.setItem('commentView', name);
    }

    if (name === 'tile') {
        document.querySelector('.view_button.list').classList.remove('active');
        document.querySelector('.view_button.tiles').classList.add('active');
        elements.forEach((el) => {
            el.classList.add('tile');
        });
    } else {
        document.querySelector('.view_button.tiles').classList.remove('active');
        document.querySelector('.view_button.list').classList.add('active');
        elements.forEach((el) => {
            el.classList.remove('tile');
        });
    }
}

let categoryArray = [];
if (document.querySelector('#categories')) {
    categoryArray = document.querySelector('#categories').value.split(',').map(Number);
    categoryArray = categoryArray.filter(number => number !== 0);
}

window.selectCategory = function (event, id) {
    const inputCategories = document.querySelector('#categories');
    let category = document.querySelector(".checkbox[data-category-id='" + id + "'] .check i")

    if (categoryArray.includes(id)) {
        category.classList.replace('fa-solid', 'fa-regular');
        category.classList.replace('fa-square-check', 'fa-square');
        categoryArray.splice(categoryArray.indexOf(id), 1);
        inputCategories.value = categoryArray;
    } else {
        category.classList.replace('fa-regular', 'fa-solid');
        category.classList.replace('fa-square', 'fa-square-check');
        categoryArray.push(id);
        inputCategories.value = categoryArray;
    }
}

let visibleCategories = false
window.categoriesToggle = function () {
    const categories_hidden = document.querySelectorAll(".checkbox.hidden");
    const toggleButton = document.querySelector(".categories_extend");

    if(visibleCategories) {
        categories_hidden.forEach((category) => {
            category.classList.remove("show");
        })
        visibleCategories = false;
        toggleButton.innerHTML = '<i class="fa-solid fa-chevron-down"></i> Pokaż więcej';
    } else {
        categories_hidden.forEach((category) => {
            category.classList.add("show");
        })
        visibleCategories = true;
        toggleButton.innerHTML = '<i class="fa-solid fa-chevron-up"></i> Ukryj';
    }
}

let userArray = [];
if (document.querySelector('#users')) {
    userArray = document.querySelector('#users').value.split(',').map(Number);
    userArray = userArray.filter(number => number !== 0);
}

window.selectUser = function (event, id) {
    let user = document.querySelector(".checkbox[data-user-id='" + id + "'] .check i")
    const inputUsers = document.querySelector('#users');
    if (userArray.includes(id)) {
        user.classList.replace('fa-solid', 'fa-regular');
        user.classList.replace('fa-square-check', 'fa-square');
        userArray.splice(userArray.indexOf(id), 1);
        inputUsers.value = userArray;
    } else {
        user.classList.replace('fa-regular', 'fa-solid');
        user.classList.replace('fa-square', 'fa-square-check');
        userArray.push(id);
        inputUsers.value = userArray;
    }
}

let searchForHighlighted = false;
let searchForNotHighlighted = false;
if (document.querySelector('#highlight')) {
    const inputHighlight = document.querySelector('#highlight');
    const cleanedValue = inputHighlight.value;
    let arrayValue = cleanedValue.split(',');
    let numericArray = arrayValue.map(Number);

    searchForHighlighted = numericArray[0] === 1;
    searchForNotHighlighted = numericArray[1] === 1;
}
window.selectHighlight = function (value) {
    let highlightValue = document.querySelector(".checkbox[data-highlight='" + value + "'] .check i");
    const inputHighlight = document.querySelector('#highlight');
    let select;

    if (value === "yes") {
        searchForHighlighted = !searchForHighlighted;
        select = searchForHighlighted;
    } else {
        searchForNotHighlighted = !searchForNotHighlighted;
        select = searchForNotHighlighted;
    }

    inputHighlight.value = [searchForHighlighted ? 1 : 0, searchForNotHighlighted ? 1 : 0];

    if (select) {
        highlightValue.classList.replace('fa-regular', 'fa-solid');
        highlightValue.classList.replace('fa-square', 'fa-square-check');
    } else {
        highlightValue.classList.replace('fa-solid', 'fa-regular');
        highlightValue.classList.replace('fa-square-check', 'fa-square');
    }

}

let rolesArray = [];
if (document.querySelector('#roles')) {
    rolesArray = document.querySelector('#roles').value.split(',').map(Number);
    rolesArray = rolesArray.filter(number => number !== 0);
}

window.selectRole = function (event, id) {
    let role = document.querySelector(".checkbox[data-role-id='" + id + "'] .check i")
    const inputRoles = document.querySelector('#roles');
    if (rolesArray.includes(id)) {
        role.classList.replace('fa-solid', 'fa-regular');
        role.classList.replace('fa-square-check', 'fa-square');
        rolesArray.splice(rolesArray.indexOf(id), 1);
        inputRoles.value = rolesArray;
    } else {
        role.classList.replace('fa-regular', 'fa-solid');
        role.classList.replace('fa-square', 'fa-square-check');
        rolesArray.push(id);
        inputRoles.value = rolesArray;
    }
}
