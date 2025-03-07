function exportarEstrutura(db_name) {
  $.ajax({
    url: "./controller/execExportacao.php",
    method: "POST",
    data: {
      db_name,
      action: "estrutura",
    },
    success: function (data) {
      console.log(data);
      $("#path-" + db_name).html(`${data}`);
    },
  });
}

function exportarCompleto(db_name) {
  $.ajax({
    url: "./controller/execExportacao.php",
    method: "POST",
    data: {
      db_name,
      action: "completo",
    },
    success: function (data) {
      console.log(data);
      // $('#path-'+db_name).html(`${data}`);
    },
  });
}
