(function ($) {
  "use strict";
  // Charger les données pour le graphique "Worldwide Sales"
  function loadWorldwideSalesChart() {
    $.ajax({
      url: "http://localhost/mmd/assets/js/benefice.php", // Chemin vers le fichier PHP
      method: "GET",
      dataType: "json",
      success: function (data) {
        var ctx2 = $("#salse-revenue").get(0).getContext("2d");
        var myChart2 = new Chart(ctx2, {
          type: "bar",
          data: data, // Utiliser les données récupérées
          options: {
            responsive: true,
          },
        });
      },
      error: function (xhr, status, error) {
        console.error("Erreur lors du chargement des données :", error);
      },
    });
  }

  // Charger les données lorsque le DOM est prêt
  $(document).ready(function () {
    loadWorldwideSalesChart();
  });
})(jQuery);
