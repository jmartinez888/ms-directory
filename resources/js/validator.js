const patterns = {
    // onlyletters: /^[A-Za-zÀ-ÿ]+$/,
    user: /^[a-zA-Z0-9]{4,16}$/,
    onlyletters: /^[(a-zA-ZÀ-ÿ)+\s?]+$/,
    words: /([A-Za-z]+ )+[A-Za-z]+$|^[A-Za-z]+$/,
    // password: /^[A-Za-z0-9+_%@!$*~-]$/, // 4 a 12 digitos.
    email: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
    phone_number: /^\d$/,
    iddoc: /^(?:\d{8}|\d{20})$/,
};

function alphaonlyinput(event) {
    var value = String.fromCharCode(event.which);
    var pattern = patterns.onlyletters;
    return pattern.test(value);
}

$("#acountry").bind("keypress", alphaonlyinput);
$("#adepartment").bind("keypress", alphaonlyinput);
$("#amunicipality").bind("keypress", alphaonlyinput);
$("#ucountry").bind("keypress", alphaonlyinput);
$("#udepartment").bind("keypress", alphaonlyinput);
$("#umunicipality").bind("keypress", alphaonlyinput);

var apasswordinput = document.getElementById("apassword");
apasswordinput.setAttribute("minlength", "8");
apasswordinput.setAttribute("maxlength", "20");
apasswordinput.setAttribute("disallowedwords", "{{username}}");

var anumericinput = document.getElementById("astate");
// apasswordinput.setAttribute("patterns", );