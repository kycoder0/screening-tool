# Screening Tool

## Description
This is a simple screening tool that allows users to input their information and see if they are eligible for a trial.

## Installation
To install the screening tool, you can clone the repository and run the following commands in the terminal:
```bash
npm install
npm run dev

php artisan migrate --seed
php artisan serve
```

## Usage
You can view the seeded questionnaires by visiting the following URLs:

- http://localhost:8000/trials/migraine-trial-questionnaire
- http://localhost:8000/trials/mental-health-questionnaire

You can also specify your own questionnaire by editing the `FormAndQuestionsSeeder.php` file in the `app/database/seeders` directory. Please be sure to also include outcomes for the questionnaire in the `FormOutcomeSeeder.php` file.
After you write your questionnaire, you can run the following command to seed the database:
```bash
php artisan migrate:refresh --seed
```
The route for your questionnaire is determined by the name. For example: `Migraine Trial Questionnaire` would be `/trials/migraine-trial-questionnaire`
## Notable Functionality
- There are two main routes: `/trials/{formId}` and `/trials/{formId}/results`. The first route is used to display the questionnaire and the second route is used to display the results of the questionnaire.
- The questionnaire is generated dynamically based on the questions specified in the database.
- The results are generated dynamically based on the outcomes specified in the database.
- Once you submit the questionnaire, a `submission` is made and an `outcome` is determined
   - The OutcomeService is a sort of 'engine' that determines the outcome based on the answers to the questions.
   - For now it simply processes multiple rules and'ed together, but it could be extended to handle more complex logic.


