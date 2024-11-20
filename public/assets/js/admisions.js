import { Results } from "./modules/request/results.mjs";

const resultsForm = document.getElementById('resultsForm');
resultsForm.addEventListener('submit',function(event){
    event.preventDefault();
    Results.verify();
})

const selectionCorrectBtn = document.getElementById('selectionCorrectBtn');
        selectionCorrectBtn.addEventListener('click', function(event){
            event.preventDefault();
            Results.getSelection();
} )

