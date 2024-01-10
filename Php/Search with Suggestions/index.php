<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Complete Search</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
   <div class="wrapper">
    <div class="search">
        <input type="text" id="searchInput" placeholder="Search...">
        <div id="suggestions">
        </div>
        <div class="icon"><ion-icon name="search-outline"></ion-icon></div>
    </div>
   </div>

   <!-- javascript -->
   <script>
    
    const searchInput = document.getElementById('searchInput');
    const suggestionsDiv = document.getElementById('suggestions');

    searchInput.addEventListener('input', function() {
        const searchTerm = searchInput.value.trim();

        // make ajax request
        if(searchTerm !== ''){
            fetch(`search.php?query=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                //Update sugestions in the UI
                const suggestions = data.map(result => ({
                    productName: result.product_name,
                    ImageUrl: result.product_img
                }));
                suggestionsDiv.classList.add("active");
                suggestionsDiv.innerHTML = getSuggestionHTML(suggestions , searchTerm);
            })
            .catch(error => console.error('Error:', error));
        }
        else{
            suggestionsDiv.classList.remove("active");
            suggestionsDiv.innerHTML = ''; //if input is empty so its shows blank
        }
    });

    function getSuggestionHTML(suggestions, searchTerm){
        if(suggestions.length === 0){
            return '<div class="not_found">No Product Found</div>';
        }
        const suggestionsList = suggestions.map(suggestion => {
            const index = suggestion.productName.toLowerCase().indexOf(searchTerm.toLowerCase());
            if(index !== -1){
                const match = suggestion.productName.substring(index, index + searchTerm.length);
                const after = suggestion.productName.substring(index + searchTerm.length);
                return `
                <li onclick="selectSuggestion('${suggestion.productName}')">
                <div class="suggest_data">
                    <img src="${suggestion.ImageUrl}" alt="${suggestion.productName}" class="suggestion_image">
                    <div class="pr_name">${match}<strong>${after}</strong></div>
                </div>
            </li>`;
            }
            return '';
        }).join('');
        return `<div>${suggestionsList}</div>`
    }


    // Select Suggestion to Get Results on the other page
    function selectSuggestion(productName){
        const encodedProductName = encodeURIComponent(productName);
        window.location.href = `search_results.php?productName=${encodedProductName}`;
        // return searchInput.value = "";  // optional
    }


    // if user hit enter so get the results 
    searchInput.addEventListener('keypress', function(e) {
        if(e.key === 'Enter'){
            const searchTerm = searchInput.value.trim().toLowerCase();
            const suggestions = suggestionsDiv.querySelectorAll('li');
            let selectedSuggestion = null;
            suggestions.forEach(suggestion => {
                // Get Suggestion Text
                const suggestionText = suggestion.textContent.trim().toLowerCase();
                //Check if the Suggestion exactly matches the entered text
                if(suggestionText === searchTerm){
                    selectedSuggestion = suggestion.textContent.trim();
                    return;     // Exit Loop if a suggestion is found
                }
            });
            if(selectedSuggestion){
                // its pass this variable value selectSuggestion on this selectsuggestion function
                selectSuggestion(selectedSuggestion);    
            }
        }
    });


    // if user click the search icon 

    document.querySelector('.icon').addEventListener('click', function (){
        const searchTerm = searchInput.value.trim().toLowerCase();
            const suggestions = suggestionsDiv.querySelectorAll('li');
            let selectedSuggestion = null;
            suggestions.forEach(suggestion => {
                // Get Suggestion Text
                const suggestionText = suggestion.textContent.trim().toLowerCase();
                //Check if the Suggestion exactly matches the entered text
                if(suggestionText === searchTerm){
                    selectedSuggestion = suggestion.textContent.trim();
                    return;     // Exit Loop if a suggestion is found
                }
            });
            if(selectedSuggestion){
                // its pass this variable value selectSuggestion on this selectsuggestion function
                selectSuggestion(selectedSuggestion);    
            }
    });




   </script>
</body>
</html>