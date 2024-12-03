class Chart{

    static generateBarChart(titulo, datos) {
      // Crear el contenedor principal
      const board = document.createElement("div");
      board.classList.add("board");
  
      // Crear el título
      const tituloGrafica = document.createElement("div");
      tituloGrafica.classList.add("titulo_grafica");
      const h3 = document.createElement("h3");
      h3.classList.add("t_grafica");
      h3.textContent = titulo;
      tituloGrafica.appendChild(h3);
  
      // Crear el sub_board
      const subBoard = document.createElement("div");
      subBoard.classList.add("sub_board");
  
      // Crear el separador
      const sepBoard = document.createElement("div");
      sepBoard.classList.add("sep_board");
      subBoard.appendChild(sepBoard);
  
      // Crear el contenedor de las barras
      const contBoard = document.createElement("div");
      contBoard.classList.add("cont_board");
  
      // Crear el contenedor de las barras
      const grafBoard = document.createElement("div");
      grafBoard.classList.add("graf_board");
  
      // Crear las barras dinámicamente según los datos
      datos.forEach((dato, index) => {
          const barra = document.createElement("div");
          barra.classList.add("barra");
  
          const subBarra = document.createElement("div");
          subBarra.classList.add("sub_barra", `b${index + 1}`);
          subBarra.style.width = `${dato.valor}%`;
  
          const tagG = document.createElement("div");
          tagG.classList.add("tag_g");
          tagG.textContent = `${dato.valor}%`;
  
          const tagLeyenda = document.createElement("div");
          tagLeyenda.classList.add("tag_leyenda");
          tagLeyenda.textContent = `día ${dato.dia}`;
  
          subBarra.appendChild(tagG);
          subBarra.appendChild(tagLeyenda);
          barra.appendChild(subBarra);
          grafBoard.appendChild(barra);
      });
  
      // Crear el contenedor de los valores en el eje Y
      const tagBoard = document.createElement("div");
      tagBoard.classList.add("tag_board");
  
      const subTagBoard = document.createElement("div");
      subTagBoard.classList.add("sub_tag_board");
  
      // Crear las marcas en el eje Y (valor)
      for (let i = 100; i >= 10; i -= 10) {
          const div = document.createElement("div");
          div.textContent = i;
          subTagBoard.appendChild(div);
      }
  
      tagBoard.appendChild(subTagBoard);
  
      // Añadir todo al subBoard
      contBoard.appendChild(grafBoard);
      contBoard.appendChild(tagBoard);
      subBoard.appendChild(contBoard);
  
      // Añadir el subBoard al contenedor principal
      board.appendChild(tituloGrafica);
      board.appendChild(subBoard);
  
      // Devolver el contenedor generado
      return board;
  }

  static generatePieChart(data) {
    if (data.length > 11) {
        console.error('The maximum number of partitions allowed is 11.');
        return null;
    }

    // Predefined color palette
    const colorPalette = [
        'var(--primary-color)', 
        'var(--secondary-color)', 
        'var(--tertiary-color)', 
        'var(--quaternary-color)', 
        'var(--quinary-color)', 
        'var(--senary-color)', 
        'var(--septenary-color)', 
        'var(--octonary-color)', 
        'var(--nonary-color)', 
        'var(--denary-color)', 
        'var(--undenary-color)'
    ];

    // Assign colors from the colorPalette
    data = data.map((item, index) => ({
        ...item,
        color: colorPalette[index] || colorPalette[colorPalette.length - 1] // Assign default color if more than 11 items
    }));

    // Check if the sum of percentages equals 100 (optional but recommended for valid pie chart)
    const totalPercentage = data.reduce((acc, item) => acc + item.percentage, 0);
    if (totalPercentage !== 100) {
        console.warn('The total percentage is not equal to 100%. Total: ' + totalPercentage + '%');
    }

    // Calculate the cumulative percentage for each section
    let cumulativePercentage = 0;

    // Create gradient stops for the conic-gradient
    const gradientStops = data.map((item, index) => {
        const start = cumulativePercentage;
        cumulativePercentage += item.percentage;
        return `${item.color} ${start}% ${cumulativePercentage}%`;
    }).join(', ');

    // Create the chart container
    const chartContainer = document.createElement('div');
    chartContainer.className = 'container_chart';

    // Create the pie chart element
    const chartElement = document.createElement('div');
    chartElement.className = 'pie_chart';
    chartElement.style.background = `conic-gradient(${gradientStops})`;
    chartContainer.appendChild(chartElement);

    // Create the legend container
    const legendContainer = document.createElement('div');
    legendContainer.className = 'legend';
    legendContainer.innerHTML = data.map(item => `
        <span class="legend_item">
            <span style="background-color: ${item.color}; width: 15px; height: 15px; border-radius: 3px; margin-right: 8px;"></span>
            <p class="text">${item.percentage}% ${item.label}</p>
        </span>
    `).join('') + `<p class="total">Total: ${data.reduce((acc, item) => acc + item.value, 0).toLocaleString()}</p>`;

    chartContainer.appendChild(legendContainer);

    return chartContainer;
}

  }




export{ Chart};