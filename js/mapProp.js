/**
 * Created by desarrollador3 on 09-05-2017.
 */


var drawingManager;
var selectedShape;
var colors = ["#1f88ff", "#FF1493", "#32CD32", "#FF8C00", "#4B0082"];
var selectedColor;
var colorButtons = {};



function clearSelection () {
    if (selectedShape) {
        if (selectedShape.type !== "marker") {
            selectedShape.setEditable(false);
        }

        selectedShape = null;
    }
}

function setSelection (shape) {
    if (shape.type !== "marker") {
        clearSelection();
        shape.setEditable(true);
        selectColor(shape.get("fillColor") || shape.get("strokeColor"));
    }

    selectedShape = shape;
}

function deleteSelectedShape () {
    if (selectedShape) {
        selectedShape.setMap(null);
    }
}

function selectColor (color) {
    selectedColor = color;
    for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        colorButtons[currColor].style.border = currColor == color ? "2px solid #789" : "2px solid #fff";
    }

    // Retrieves the current options from the drawing manager and replaces the
    // stroke or fill color as appropriate.
    var polylineOptions = drawingManager.get("polylineOptions");
    polylineOptions.strokeColor = color;
    drawingManager.set("polylineOptions", polylineOptions);

    var rectangleOptions = drawingManager.get("rectangleOptions");
    rectangleOptions.fillColor = color;
    drawingManager.set("rectangleOptions", rectangleOptions);

    var circleOptions = drawingManager.get("circleOptions");
    circleOptions.fillColor = color;
    drawingManager.set("circleOptions", circleOptions);

    var polygonOptions = drawingManager.get("polygonOptions");
    polygonOptions.fillColor = color;
    drawingManager.set("polygonOptions", polygonOptions);
}

function setSelectedShapeColor (color) {
    if (selectedShape) {
        if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) {
            selectedShape.set("strokeColor", color);
        } else {
            selectedShape.set("fillColor", color);
        }
    }
}

function makeColorButton (color) {
    var button = document.createElement("span");
    button.className = "color-button";
    button.style.backgroundColor = color;
    google.maps.event.addDomListener(button, "click", function () {
        selectColor(color);
        setSelectedShapeColor(color);
    });

    return button;
}

function buildColorPalette () {
    var colorPalette = document.getElementById("color-palette");
    for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        var colorButton = makeColorButton(currColor);
        colorPalette.appendChild(colorButton);
        colorButtons[currColor] = colorButton;
    }
    selectColor(colors[0]);
}

function initialize () {
    var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: new google.maps.LatLng(-33.473878, -70.616170)
    });

    var polyOptions = {
        strokeWeight: 0,
        fillOpacity: 0.45,
        editable: true,
        draggable: false,
        clickable: true
    };
    // Creates a drawing manager attached to the map that allows the user to draw
    // markers, lines, and shapes.
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        markerOptions: {
            draggable: false,
            clickable: true
        },
        polylineOptions: {
            editable: true,
            draggable: false,
            clickable: true
        },
        rectangleOptions: polyOptions,
        circleOptions: polyOptions,
        polygonOptions: polyOptions,
        map: map
    });

    google.maps.event.addListener(drawingManager, "overlaycomplete", function (e) {
        var newShape = e.overlay;

        newShape.type = e.type;

        if (e.type !== google.maps.drawing.OverlayType.MARKER) {
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);

            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            google.maps.event.addListener(newShape, "click", function (e) {
                if (e.vertex !== undefined) {
                    if (newShape.type === google.maps.drawing.OverlayType.POLYGON) {
                        var path = newShape.getPaths().getAt(e.path);
                        path.removeAt(e.vertex);
                        if (path.length < 3) {
                            newShape.setMap(null);
                        }
                    }
                    if (newShape.type === google.maps.drawing.OverlayType.POLYLINE) {
                        var path = newShape.getPath();
                        path.removeAt(e.vertex);
                        if (path.length < 2) {
                            newShape.setMap(null);
                        }
                    }
                }
                setSelection(newShape);
            });
            setSelection(newShape);
        }

        else {
            google.maps.event.addListener(newShape, "click", function (e) {
                setSelection(newShape);
            });
            setSelection(newShape);
        }
    });




    // Clear the current selection when the drawing mode is changed, or when the
    // map is clicked.
    google.maps.event.addListener(drawingManager, "drawingmo de_changed", clearSelection);
    google.maps.event.addListener(map, "click", clearSelection);
    google.maps.event.addDomListener(document.getElementById("delete-button"), "click", deleteSelectedShape);


    // funcion para obtener las coordenadas de el poligono
    google.maps.event.addListener(drawingManager, "polygoncomplete", function (polygon) {
        var coordinates = (polygon.getPath().getArray().toString());

        var arr1 = coordinates.split("),(");
        var arraycord = [];
        for(i=0;i<arr1.length;i++){
            dat = arr1[i].replace("(","").replace(")","");
            lat_long = dat.split(",");
            arraycord[i] = {"lat":lat_long[0],"lng":lat_long[1]}

        }

        jsonCord = JSON.stringify(arraycord);
        console.log(jsonCord);
        console.log("------------------------------");
        decodejson = JSON.parse(jsonCord);

    });

    // funcion para obtener las coordenadas de las polilineas
    google.maps.event.addListener(drawingManager, "polylinecomplete", function (polyline) {
        var coordinates = (polyline.getPath().getArray().toString());
        
       
        var arr1 = coordinates.split("),(");
        var arraycord = [];
        for(i=0;i<arr1.length;i++){
            dat = arr1[i].replace("(","").replace(")","");
            lat_long = dat.split(",");
            arraycord[i] = {"lat":lat_long[0],"lng":lat_long[1]}

        }

        jsonCord = JSON.stringify(arraycord);
        console.log(jsonCord);
        console.log("------------------------------");
        decodejson = JSON.parse(jsonCord);



      var parametros = {
                "puntos" : jsonCord
                
        };
        
        console.log("inicia");
        $.ajax({
                data:  parametros,
                url:   "/newCoords",
                type:  "post",
                beforeSend: function SaveCoords() {
                       console.log("cargando");
                },
                success:  function SaveCoords(response) {
                       console.log(response);
                        resul = JSON.parse(response);
                        if(resul.status){
                            alert("se actulizo correctamente");
                        }else{
                            alert("error al actualizar");
                        }
                }
        });

     console.log("finaliza");


    });

     buildColorPalette();
}
google.maps.event.addDomListener(window, "load", initialize);




