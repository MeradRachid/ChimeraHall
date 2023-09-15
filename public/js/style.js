/* 
 Ce code sélectionne tous les éléments avec les id “swipop”, “swipup” et “swipap” 
 il ajoute un gestionnaire d’événements “click” à chacun d’entre eux. 
 Et lorsque l’un de ces éléments est cliqué, la page défilera vers le haut avec un comportement fluide. 
*/
const scrollToTopButton = document.querySelectorAll('#swipop, #swipup, #swipap'); // retourne un objet NodeList composé de plusieurs éléments.

scrollToTopButton.forEach(button => {
  button.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth' // Pour un défilement fluide, utilisez 'smooth' ; pour un défilement instantané, utilisez 'auto' ou 'instant'
    });
  });
});


// Bloque la saisie de caractères non numériques à mesure qu'ils sont entrés
function isNumber(event) {
  var keycode = event.keyCode;
  if (keycode > 31 && (keycode < 48 || keycode > 57)) {
    return false;
  }
  return true;
}

/* When the user clicks on the button, toggle between hiding and showing the dropdown content */
// function toggleDropdown()
// {
//     document.getElementById("exploreContent").classList.toggle("show");
// }



// // Close the dropdown if the user clicks outside of it
// window.onclick = function (event)
// {
//     if (!event.target.matches('.dropbtn'))
//     {
//         var dropdowns = document.getElementsByClassName("dropdown-content");
//         var i;
//         for (i = 0; i < dropdowns.length; i++)
//         {
//             var openDropdown = dropdowns[i];
//             if (openDropdown.classList.contains('show'))
//             {
//                 openDropdown.classList.remove('show');
//             }
//         }
//     }
// }




// function toggleDropdown()
// {
//     document.getElementById("exploreContent").classList.toggle("show");
// }

// // Ajouter un événement de clic sur le bouton
// document.getElementById("ShowHideButton").onclick = toggleDropdown;

// // Close the dropdown if the user clicks outside of it
// window.onclick = function(event)
// {
//     if (!event.target.matches('.dropbtn'))
//     {
//         var dropdowns = document.getElementsByClassName("dropdown-content");
//         var i;
//         for (i = 0; i < dropdowns.length; i++)
//         {
//             var openDropdown = dropdowns[i];
//             if (openDropdown.classList.contains('show'))
//             {
//                 openDropdown.classList.remove('show');
//             }
//         }
//     }
// }



// Use the Class mtgtooltip pour afficher des infos sur une carte en particulier
// document.addEventListener('DOMContentLoaded', function () { example = new mtgTooltip(); });
