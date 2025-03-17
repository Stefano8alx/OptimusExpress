$(document).ready(function () {
  // ==================== EMPLEADOS ====================
  // Cargar lista de empleados
  $("#cargarEmpleados").click(function () {
      $.ajax({
          url: "obtener_empleados.php",
          method: "GET",
          success: function (data) {
              $("#listaEmpleadosContainer").html(data);
          },
          error: function () {
              alert("Error al cargar los empleados.");
          }
      });
  });

  // Registrar nuevo empleado
  $("#formRegistroEmpleado").submit(function (e) {
      e.preventDefault();
      $.ajax({
          url: "empleados.php",
          method: "POST",
          data: $(this).serialize(),
          success: function (response) {
              $("#mensajeEmpleado").html(response);
              $("#nombreEmpleado").val(""); // Limpiar campo
          }
      });
  });

  // ==================== LAVADOS ====================
  // Registrar nuevo lavado
  $("#formRegistroLavado").submit(function (e) {
      e.preventDefault();
      $.ajax({
          url: "lavados.php",
          method: "POST",
          data: $(this).serialize(),
          success: function (response) {
              $("#mensajeLavado").html(response);
              // Limpiar todos los campos
              $("#idEmpleadoLavado, #placaVehiculo, #precioLavado, #fechaLavado").val("");
          }
      });
  });

  // Buscar lavados por empleado y fecha
  $("#formBuscarLavados").submit(function (e) {
      e.preventDefault();
      const idEmpleado = $("#idEmpleado").val();
      const fechaSeleccionada = $("#fechaSeleccionada").val();

      if (!idEmpleado) {
          alert("❌ Por favor ingresa un ID de empleado");
          return;
      }

      $.ajax({
          url: "listar_lavados.php",
          method: "GET",
          data: { 
              idEmpleado: idEmpleado,
              fechaSeleccionada: fechaSeleccionada 
          },
          success: function (data) {
              $("#listaLavadosContainer").html(data);
          },
          error: function () {
              alert("Error al cargar lavados");
          }
      });
  });

  // ==================== CÁLCULOS ====================
  // Calcular 40% del empleado específico
  $("#calcular40Porciento").click(function () {
      const idEmpleado = $("#idEmpleado").val();
      const fechaSeleccionada = $("#fechaSeleccionada").val();

      if (!idEmpleado || !fechaSeleccionada) {
          alert("❌ Debes ingresar ID de empleado y seleccionar fecha");
          return;
      }

      $.ajax({
          url: "calcular_40_porciento.php",
          method: "GET",
          data: { 
              idEmpleado: idEmpleado,
              fechaSeleccionada: fechaSeleccionada 
          },
          success: function (response) {
              $("#resultado40Porciento").html(`<div class="alert alert-success">${response}</div>`);
          }
      });
  });

  // Calcular 60% global del día
  $("#calcular60PorcientoGlobal").click(function () {
      const fechaSeleccionada = $("#fechaSeleccionada").val();

      if (!fechaSeleccionada) {
          alert("❌ Debes seleccionar una fecha");
          return;
      }

      $.ajax({
          url: "calcular_60_global.php",
          method: "GET",
          data: { fechaSeleccionada: fechaSeleccionada },
          success: function (response) {
              $("#resultado60PorcientoGlobal").html(`<div class="alert alert-info">${response}</div>`);
          }
      });
  });

  // ==================== PAGOS ====================
  // Cargar lavados pendientes solo en la página de pagos
  if (window.location.pathname.includes("pagos.html")) {
      cargarLavadosPendientes();
  }

  function cargarLavadosPendientes() {
      $.ajax({
          url: "obtener_pendientes.php",
          method: "GET",
          success: function (data) {
              $("#listaPendientesContainer").html(data);
          }
      });
  }

  // Función global para marcar como pagado
  window.marcarComoPagado = function(idLavado) {
      if (!confirm("¿Confirmar pago del lavado #" + idLavado + "?")) return;

      $.ajax({
          url: "marcar_pagado.php",
          method: "POST",
          data: { id: idLavado },
          success: function() {
              $(`#lavado-${idLavado}`).fadeOut(300, function() {
                  $(this).remove();
              });
          }
      });
  }

  // ==================== REPORTES ====================
  // Descargar reporte CSV
  $("#descargarCSV").click(function () {
      const fechaSeleccionada = $("#fechaSeleccionada").val();

      if (!fechaSeleccionada) {
          alert("❌ Selecciona una fecha primero");
          return;
      }

      window.location.href = `generar_reporte_csv.php?fechaSeleccionada=${fechaSeleccionada}`;
  });
});