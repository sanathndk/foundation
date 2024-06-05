// Function to Author search
function Author(str,resultContainerId) {  
    if (str.length == 0) {
        document.getElementById("resultauthor").innerHTML = "";
        document.getElementById("resultauthor").style.display = "none";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("resultauthor").innerHTML = this.responseText;
                document.getElementById("resultauthor").style.display = "block";
            }
        };
        var resultContainerId="a";
        xmlhttp.open("GET", "search.php?q=" + str + "&field=" + resultContainerId, true);
        xmlhttp.send();
    }
}

// Event listener for input changes
document.getElementById("author").addEventListener("input", function() {
    Author(this.value);

});

// Event listener to handle result item clicks
document.getElementById("resultauthor").addEventListener("click", function(e) {
    if (e.target.classList.contains("result-item")) {
        document.getElementById("author").value = e.target.textContent;
        this.style.display = "none";

    }
});

// **********************
// Function to Author 2 search
function Author2(str,resultContainerId) {  
    if (str.length == 0) {
        document.getElementById("resultauthor2").innerHTML = "";
        document.getElementById("resultauthor2").style.display = "none";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("resultauthor2").innerHTML = this.responseText;
                document.getElementById("resultauthor2").style.display = "block";
            }
        };
        var resultContainerId="a";
        xmlhttp.open("GET", "search.php?q=" + str + "&field=" + resultContainerId, true);
        xmlhttp.send();
    }
}

// Event listener for input changes
document.getElementById("author2").addEventListener("input", function() {
    Author2(this.value);

});

// Event listener to handle result item clicks
document.getElementById("resultauthor2").addEventListener("click", function(e) {
    if (e.target.classList.contains("result-item")) {
        document.getElementById("author2").value = e.target.textContent;
        this.style.display = "none";

    }
});

// ************Publisher search************
function publisher(str,resultContainerId) {  
    var resultContainerId="b";
    if (str.length == 0) {
        document.getElementById("resultpublisher").innerHTML = "";
        document.getElementById("resultpublisher").style.display = "none";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("resultpublisher").innerHTML = this.responseText;
                document.getElementById("resultpublisher").style.display = "block";
            }
        };
        xmlhttp.open("GET", "search.php?q=" + str + "&field=" + resultContainerId, true);
        xmlhttp.send();
    }
}

// Event listener for input changes
document.getElementById("inputpublisher").addEventListener("input", function() {
    publisher(this.value);

});

// Event listener to handle result item clicks
document.getElementById("resultpublisher").addEventListener("click", function(e) {
    if (e.target.classList.contains("result-item")) {
        document.getElementById("inputpublisher").value = e.target.textContent;
        this.style.display = "none";

    }
});



// ************Country search************

function country(str,resultContainerId) {  
    var resultContainerId="c";
    if (str.length == 0) {
        document.getElementById("resultcountry").innerHTML = "";
        document.getElementById("resultcountry").style.display = "none";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("resultcountry").innerHTML = this.responseText;
                document.getElementById("resultcountry").style.display = "block";
            }
        };
        xmlhttp.open("GET", "search.php?q=" + str + "&field=" + resultContainerId, true);
        xmlhttp.send();
    }
}

// Event listener for input changes
document.getElementById("country").addEventListener("input", function() {
    country(this.value);

});

// Event listener to handle result item clicks
document.getElementById("resultcountry").addEventListener("click", function(e) {
    if (e.target.classList.contains("result-item")) {
        document.getElementById("country").value = e.target.textContent;
        this.style.display = "none";

    }
});

function altcountry(str,resultContainerId) {  
    var resultContainerId="c";
    if (str.length == 0) {
        document.getElementById("resultaltcountry").innerHTML = "";
        document.getElementById("resultaltcountry").style.display = "none";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("resultaltcountry").innerHTML = this.responseText;
                document.getElementById("resultaltcountry").style.display = "block";
            }
        };
        xmlhttp.open("GET", "search.php?q=" + str + "&field=" + resultContainerId, true);
        xmlhttp.send();
    }
}

// Event listener for input changes
document.getElementById("altcountry").addEventListener("input", function() {
    altcountry(this.value);

});

// Event listener to handle result item clicks
document.getElementById("resultaltcountry").addEventListener("click", function(e) {
    if (e.target.classList.contains("result-item")) {
        document.getElementById("altcountry").value = e.target.textContent;
        this.style.display = "none";

    }
});