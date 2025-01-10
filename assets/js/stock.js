(function ($) {
  "use strict";
  // Charger les données pour le graphique "Worldwide Sales"
  function loadWorldwideSalesChart() {
    $.ajax({
      url: "http://localhost/mmd/assets/js/stock.php", // Chemin vers le fichier PHP
      method: "GET",
      dataType: "json",
      success: function (data) {
        var ctx4 = $("#doughnut-chart").get(0).getContext("2d");
        var myChart4 = new Chart(ctx4, {
          type: "doughnut",
          data: data, // Utiliser les données récupérées
          options: {
            responsive: false,
            plugins: {
              legend: {
                position: "right", // Aligner les légendes à droite
                labels: {
                  boxWidth: 20, // Taille de la boîte de couleur
                  padding: 15, // Espacement entre les légendes
                },
              },
            },
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
