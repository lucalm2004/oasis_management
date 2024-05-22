<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a7409f463b.js" crossorigin="anonymous"></script>
</head>
<style>

    #downloadPdfButton{
      position: absolute;
    left: 90%;
    cursor: pointer;

    }
    body {
        background: #10101a;
        font-family: 'Montserrat', sans-serif;
        margin: 0;
        padding: 0;
    
    }

    .ticket {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 700px;
        margin: 20px auto;
        padding-top: 20%;
        padding-bottom: 70%;

        .stub,
        .check {
            box-sizing: border-box;
        }
    }

    .stub {
        background: #F5763B;
        height: 250px;
        width: 250px;
        color: white;
        padding: 20px;
        position: relative;

        &:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            border-top: 20px solid #10101a;
            border-left: 20px solid #ef5658;
            width: 0;
        }

        &:after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            border-bottom: 20px solid #10101a;
            border-left: 20px solid #ef5658;
            width: 0;
        }

        .top {
            display: flex;
            align-items: center;
            height: 40px;
            text-transform: uppercase;

            .line {
                display: block;
                background: #fff;
                height: 40px;
                width: 3px;
                margin: 0 20px;
            }

            .num {
                font-size: 10px;

                span {
                    color: #000;
                }
            }
        }

        .number {
            position: absolute;
            /* left: 40px; */
            top: 70px;
            font-size: 150px;
        }

        .invite {
            position: absolute;
            left: 150px;
            bottom: 45px;
            color: #000;
            width: 20%;

            &:before {
                content: '';
                background: #fff;
                display: block;
                width: 40px;
                height: 3px;
                margin-bottom: 5px;
            }
        }
    }

    .check {
        background: #fff;
        height: 250px;
        width: 500px;
        padding: 40px;
        position: relative;

        &:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            border-top: 20px solid #10101a;
            border-right: 20px solid #fff;
            width: 0;
        }

        &:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            border-bottom: 20px solid #10101a;
            border-right: 20px solid #fff;
            width: 0;
        }

        .big {
            font-size: 80px;
            font-weight: 900;
            line-height: .8em;
        }

        .number {
            position: absolute;
            top: 50px;
            right: 50px;
            color: #ef5658;
            font-size: 40px;
        }

        .info {
            display: flex;
            justify-content: flex-start;

            font-size: 12px;
            margin-top: 20px;
            width: 100%;

            section {
                margin-right: 50px;

                &:before {
                    content: '';
                    background: #ef5658;
                    display: block;
                    width: 40px;
                    height: 3px;
                    margin-bottom: 5px;
                }

                .title {
                    font-size: 10px;
                    text-transform: uppercase;
                }
            }
        }
    }
</style>

<body>
  {{-- <button id="downloadPdfButton">Descargar PDF</button> --}}
    <div class="ticket">
        <div class="stub">
            <div class="top">
                <span class="admit">Total: <?php echo isset($_GET['precioTotal']) ? $_GET['precioTotal'] . '€' : 'N/A'; ?></span>
                <span class="line"></span>
                <span class="num">
                    Nº de entrada:
                    <span>
                      <?php
                      if (isset($_GET['codigo'])) {
                          echo $_GET['codigo'];
                      } else {
                          echo $codigo;
                      }
                      ?>
                      
                    </span>
                </span>
            </div>
            <div class="number">
              <?php
              if (isset($_GET['dia'])) {
                echo "#1";              
              
              } else {
                  echo "$qr";              

              }
              ?>
                <?php  ?>
            </div>
            <div class="invite">
                <?php echo $_GET['discoteca']; ?> </div>
        </div>
        <div class="check">
          <i id="downloadPdfButton" class="fa-solid fa-file-arrow-down"></i>

            <div class="big">
                <?php echo $_GET['nombreEvento']; ?>
            </div>
            <div class="number">#1</div>
            <div class="info">
                <section>
                    <div class="title">Dia:</div>
                    <div>
                      <?php
if (isset($_GET['dia'])) {
                echo $_GET['dia'];              
              
              } else {
                  echo "$fechaHora ";              

              }
                      ?>
                      
                   </div>
                </section>
                <section>
                    <div class="title">Entrada de:</div>
                    <div><?php $user = Auth::user();
                    echo $user->name; ?></div>
                </section>
                <section>
                    <div class="title">Discoteca:</div>
                    <div><?php echo $_GET['discoteca']; ?></div>
                </section>
            </div>
        </div>
    </div>




    
    <script>
      document.getElementById('downloadPdfButton').addEventListener('click', function() {
          // Selecciona el elemento HTML que deseas convertir a PDF
          var element = document.body;
          
          // Opciones para la conversión
          var options = {
              filename: 'documento.pdf',
              image: { type: 'jpeg', quality: 0.98 },
              html2canvas: { scale: 2 },
              jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
          };
          
          // Inicia la conversión a PDF
          html2pdf().from(element).set(options).save();
      });
      </script>
      
</body>

</html>
