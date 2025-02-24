
// Regular expressions to validate the fields
const regular_expressions = {
    // Name can contain only alphabetic characters or letters with accents, allowing 1 or 3 words
    name: /^[A-Za-záéíóúÁÉÍÓÚñÑ]+(?: [A-Za-záéíóúÁÉÍÓÚñÑ]+){0,2}$/, 
    // Last name follows the same pattern as the name (alphabetic characters and accents)
    LastName: /^[A-Za-záéíóúÁÉÍÓÚ]+(?: [A-Za-záéíóúÁÉÍÓÚ]+)?$/, 
    // ID number pattern (for example, specific to some country ID formats)
    idNum: /^(0[1-9]|1[0-8])[0-2][0-8](1|2)(0|9)\d{7}$/, 
    // Phone number pattern for a valid number starting with specific digits
    phone: /^(9|8|7|3)\d{7}$/, 
    // Email validation (standard email format)
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, 
    // Address validation (letters, numbers, spaces, commas, periods, and hyphens)
    address: /^[^\s][a-zA-ZáéíóúÁÉÍÓÚÜü0-9\s,.-]*$/, 

    // Password must be at least 8 characters long and contain:
    // - at least one lowercase letter
    // - at least one uppercase letter
    // - at least one digit
    // - at least one special character: +, *, -, or _
    
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[+*\-_])[A-Za-z\d+*\-_]{8,}$/,

    urlYoutube: /^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[\w\-]{11}$/
   
};



export{regular_expressions};