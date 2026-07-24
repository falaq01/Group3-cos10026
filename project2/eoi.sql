CREATE TABLE eoi
(
  EOInumber INT AUTO_INCREMENT PRIMARY KEY,
  jobRefNumber VARCHAR(5) NOT NULL,
  firstName VARCHAR(20) NOT NULL,
  lastName VARCHAR(20) NOT NULL,
  dob VARCHAR(10) NOT NULL,
  gender VARCHAR(20) NOT NULL,
  streetAddress VARCHAR(40)NOT NULL,
  suburb VARCHAR(40) NOT NULL,
  state VARCHAR(3) NOT NULL,
  postcode VARCHAR(4) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(12) NOT NULL,
  skills VARCHAR(255) NOT NULL,
  otherSkills TEXT NULL,
  status ENUM('New','Current','Final') DEFAULT 'New'
)
