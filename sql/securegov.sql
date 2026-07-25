-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2026 at 03:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `securegov`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `memberID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `studentId` varchar(20) NOT NULL,
  `contribution` text NOT NULL,
  `hometown` varchar(50) NOT NULL,
  `codingSnack` varchar(50) NOT NULL,
  `part1` text NOT NULL,
  `part2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`memberID`, `name`, `studentId`, `contribution`, `hometown`, `codingSnack`, `part1`, `part2`) VALUES
(1, 'Falaq Nadeem', '106194653', 'Home page, application form layout, GitHub deployment', 'Pakistan', 'Hot Cheetos', 'Designed and built the home page (index.html); Laid out the structure of the application form; Managed the GitHub repository deployment', 'Converting all pages to PHP; Building the shared header/nav/footer includes; Consolidating the site\'s CSS into a single stylesheet; Managing the database connection; Building the about page\'s dynamic query'),
(2, 'Mohamed Ali', '106404909', 'Jobs page, application form submission, Jira board', 'Tunisia', 'Ice Cream', 'Built the jobs listing page; Built the application form\'s submission handling; Maintained the team\'s Jira board', 'Converting jobs.php to render all job postings dynamically from MySQL; Creating the jobs database table; Building the password-protected HR manager dashboard (manage.php) with filtering, sorting, and status-update features'),
(3, 'Syed', '106195737', 'Application form fields and validation', 'India', 'Batatos', 'Built the application form\'s fields; Built the form\'s client-side validation', 'Removing all client-side validation; Rewriting it entirely as server-side validation and sanitisation in process_eoi.php; Creating the eoi database table and inserting validated applicant records'),
(4, 'Hend', '106194174', 'About page', 'Qatar', 'Red Bull', 'Designed and built the About page; Built the team profile section', 'Wrote the CREATE TABLE SQL statements for both the eoi and about database tables; Rebuilt the about page\'s front-end HTML and CSS structure to display member data dynamically from the database');

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `jobRefNumber` varchar(5) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `dob` varchar(10) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `streetAddress` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `skills` varchar(255) NOT NULL,
  `otherSkills` text DEFAULT NULL,
  `status` enum('New','Current','Final') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `jobRefNumber`, `firstName`, `lastName`, `dob`, `gender`, `streetAddress`, `suburb`, `state`, `postcode`, `email`, `phone`, `skills`, `otherSkills`, `status`) VALUES
(13, 'SG123', 'Amanda', 'Snow', '25/09/2003', 'Female', 'George street', 'wukair', 'AWK', '0000', 'Amandasnow78@gmail.com', '8886667774', 'Programming / scripting, Other', 'Test', 'New'),
(14, 'SG123', 'Falaq', 'Nadeem', '12/01/1998', 'Female', '123 Test Street', 'wukair', 'AWK', '1234', 'test@example.com', '12345678', 'Cloud computing', '', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `job_ref` varchar(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `salary_range` varchar(50) NOT NULL,
  `reporting_to` varchar(100) NOT NULL,
  `classification` varchar(50) NOT NULL,
  `closes` date NOT NULL,
  `badge_clearance` varchar(100) NOT NULL,
  `badge_type` varchar(50) NOT NULL,
  `overview` text NOT NULL,
  `responsibilities` text NOT NULL,
  `essential` text NOT NULL,
  `preferable` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `job_ref`, `title`, `description`, `salary_range`, `reporting_to`, `classification`, `closes`, `badge_clearance`, `badge_type`, `overview`, `responsibilities`, `essential`, `preferable`) VALUES
(4, 'SG321', 'Cyber Threat Intelligence Analyst', 'Monitor, detect, and analyze actionable cyber threat intelligence targeting Qatar\'s critical national infrastructure, government networks, and key economic sectors.', 'QAR 22,000 – QAR 32,000 pm', 'Head of National Threat Analysis Center', 'Grade 6 (Technical)', '2026-07-25', 'National Security Clearance Required', 'Full-Time · Permanent', 'The Cyber Threat Intelligence Analyst works within the strategic core of our defense infrastructure. You will be responsible for tracking adversary Tactics, Techniques, and Procedures (TTPs) specific to the Gulf region. The role involves processing complex technical telemetry from global and local threat feeds and turning it into actionable security alerts for state entities.\n\nDue to the sensitive nature of the data involved, candidate processing includes a mandatory Ministry of Interior (MOI) background vetting check before final onboarding.', '1. Triage and evaluate daily threat intelligence feeds spanning OSINT, commercial vendors, and strategic regional partners.|2. Produce operational intelligence reports, including localized threat briefs, vulnerability warnings, and executive risk summaries.|3. Map threat actor behaviors against the MITRE ATT&CK framework to identify gaps in national defensive postures.|4. Collaborate with the National SOC during active investigations to provide historical and contextual threat profiles.|5. Maintain technical integrations for automated threat data exchange across local government platforms.', 'Bachelor\'s degree in Computer Science, Cybersecurity, or Software Engineering|3+ years of direct experience in a dedicated SOC or Threat Intelligence unit|Strong grasp of network protocols, traffic analysis, and structural log analysis|Fluency in professional English (written and spoken)|Must satisfy local security vetting criteria', 'Professional certifications: GCTI, GCIA, or CEH|Experience operating threat management platforms like MISP or OpenCTI|Fluency in Arabic is highly advantageous for inter-agency liaison tasks|Prior exposure to Critical National Infrastructure (CNI) security protocols'),
(5, 'SG123', 'Senior Cloud Security Architect', 'Lead the secure structural design, implementation, and assessment of government cloud infrastructure to ensure full compliance with the Qatar National Information Assurance (NIA) Policy.', 'QAR 38,000 – QAR 52,000 pm', 'Director of Cloud Security Compliance', 'Grade 3 (Senior Expert)', '2026-08-01', 'Special Vetting Clearance Required', 'Full-Time · Contract', 'As the Senior Cloud Security Architect, you will act as the principal technical authority ensuring government migration strategies adhere to rigorous national sovereignty standards. You will craft scalable security blueprints for multi-cloud deployments (Azure, AWS, and private clouds), shielding protected state data workloads.\n\nThis senior position guides overarching engineering practices and directly mentors mid-tier security engineers on modern cloud governance.', '1. Architect secure landing zones and blueprints across hybrid environments keeping data sovereignty policies at the center.|2. Perform deep structural audits on cloud architectures to enforce the State\'s cloud security compliance baselines.|3. Embed DevSecOps practices by configuring automated guardrails via Infrastructure as Code (IaC) pipelines.|4. Deliver technical consultancy to institutional IT leaders regarding data isolation, identity management, and threat modeling.|5. Direct and upskill a small engineering cohort in advanced cloud validation and logging practices.', '7+ years in Information Security, with at least 3 years explicitly modeling or securing public cloud instances|Direct deployment experience with major cloud suites (Microsoft Azure or AWS)|Demonstrated knowledge of Qatar National Information Assurance (NIA) guidelines or equivalent international compliance standards (ISO 27001)|Proven capabilities managing cloud configuration monitoring platforms', 'Master\'s Degree in Cybersecurity, Computer Networks, or an engineering equivalent|Industry standard certifications: CCSP, AWS Certified Security Specialty, or AZ-500|Familiarity with modern deployment security checks (Terraform, Checkov, or Snyk)|Bilingual proficiency (Arabic and English) is highly preferred');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(1, 'Admin', '$2y$10$92wxb/Vlsry46wIOFlh7veSyF5qTLB7.1qscvtd2hhfOAy4ActZIu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`memberID`);

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `memberID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
