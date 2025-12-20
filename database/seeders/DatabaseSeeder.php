<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            'General Knowledge',
            'Science',
            'Mathematics',
            'History',
            'Programming'
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);
        }

        // Create Sample Questions
        $questionsData = [
            [
                'category' => 'General Knowledge',
                'question_text' => '<h3>What is the capital of France?</h3><p>Choose the correct answer from the options below:</p>',
                'difficulty' => 'Easy',
                'marks' => 1,
                'status' => 'Published',
                'options' => [
                    ['text' => 'London', 'correct' => false],
                    ['text' => 'Paris', 'correct' => true],
                    ['text' => 'Berlin', 'correct' => false],
                    ['text' => 'Madrid', 'correct' => false],
                ]
            ],
            [
                'category' => 'Science',
                'question_text' => '<h3>Which planet is known as the Red Planet?</h3><p>Select the correct answer:</p><ul><li>Consider the color</li><li>Think about Mars exploration</li></ul>',
                'difficulty' => 'Easy',
                'marks' => 1,
                'status' => 'Published',
                'options' => [
                    ['text' => 'Venus', 'correct' => false],
                    ['text' => 'Mars', 'correct' => true],
                    ['text' => 'Jupiter', 'correct' => false],
                    ['text' => 'Saturn', 'correct' => false],
                ]
            ],
            [
                'category' => 'Mathematics',
                'question_text' => '<h3>What is the value of π (pi)?</h3><table><tr><th>Option</th><th>Value</th></tr><tr><td>A</td><td>3.14159</td></tr><tr><td>B</td><td>2.71828</td></tr></table>',
                'difficulty' => 'Easy',
                'marks' => 2,
                'status' => 'Published',
                'options' => [
                    ['text' => '3.14159', 'correct' => true],
                    ['text' => '2.71828', 'correct' => false],
                    ['text' => '1.41421', 'correct' => false],
                    ['text' => '1.61803', 'correct' => false],
                ]
            ],
            [
                'category' => 'History',
                'question_text' => '<h3>When did World War II end?</h3><p><strong>Important dates to consider:</strong></p><ol><li>1939 - Start of WWII</li><li>1941 - Pearl Harbor</li><li>1945 - End of WWII</li></ol>',
                'difficulty' => 'Medium',
                'marks' => 2,
                'status' => 'Published',
                'options' => [
                    ['text' => '1943', 'correct' => false],
                    ['text' => '1944', 'correct' => false],
                    ['text' => '1945', 'correct' => true],
                    ['text' => '1946', 'correct' => false],
                ]
            ],
            [
                'category' => 'Programming',
                'question_text' => '<h3>Which of the following is a programming language?</h3><p><em>Select the language used for web development:</em></p>',
                'difficulty' => 'Easy',
                'marks' => 1,
                'status' => 'Published',
                'options' => [
                    ['text' => 'HTML', 'correct' => false],
                    ['text' => 'CSS', 'correct' => false],
                    ['text' => 'JavaScript', 'correct' => true],
                    ['text' => 'JSON', 'correct' => false],
                ]
            ],
            [
                'category' => 'General Knowledge',
                'question_text' => '<h3>What is the largest ocean on Earth?</h3><p>Consider the following facts:</p><table border="1"><tr><th>Ocean</th><th>Area (km²)</th></tr><tr><td>Pacific</td><td>165.2 million</td></tr><tr><td>Atlantic</td><td>106.5 million</td></tr><tr><td>Indian</td><td>73.4 million</td></tr></table>',
                'difficulty' => 'Medium',
                'marks' => 2,
                'status' => 'Published',
                'options' => [
                    ['text' => 'Atlantic Ocean', 'correct' => false],
                    ['text' => 'Indian Ocean', 'correct' => false],
                    ['text' => 'Pacific Ocean', 'correct' => true],
                    ['text' => 'Arctic Ocean', 'correct' => false],
                ]
            ],
            [
                'category' => 'Science',
                'question_text' => '<h3>What is the chemical formula for water?</h3><p><strong>Chemical composition:</strong></p><ul><li>Hydrogen: 2 atoms</li><li>Oxygen: 1 atom</li></ul>',
                'difficulty' => 'Easy',
                'marks' => 1,
                'status' => 'Published',
                'options' => [
                    ['text' => 'H2O', 'correct' => true],
                    ['text' => 'CO2', 'correct' => false],
                    ['text' => 'O2', 'correct' => false],
                    ['text' => 'H2SO4', 'correct' => false],
                ]
            ],
            [
                'category' => 'Mathematics',
                'question_text' => '<h3>What is the square root of 144?</h3><p>Calculate the answer carefully.</p>',
                'difficulty' => 'Easy',
                'marks' => 1,
                'status' => 'Draft',
                'options' => [
                    ['text' => '10', 'correct' => false],
                    ['text' => '11', 'correct' => false],
                    ['text' => '12', 'correct' => true],
                    ['text' => '13', 'correct' => false],
                ]
            ],
            [
                'category' => 'History',
                'question_text' => '<h3>Who was the first President of the United States?</h3><p>Key historical figures:</p><ol><li>Founding Fathers</li><li>Constitutional Convention</li><li>First Presidential Election (1789)</li></ol>',
                'difficulty' => 'Medium',
                'marks' => 2,
                'status' => 'Published',
                'options' => [
                    ['text' => 'Thomas Jefferson', 'correct' => false],
                    ['text' => 'George Washington', 'correct' => true],
                    ['text' => 'John Adams', 'correct' => false],
                    ['text' => 'Benjamin Franklin', 'correct' => false],
                ]
            ],
            [
                'category' => 'Programming',
                'question_text' => '<h3>What does OOP stand for in programming?</h3><p><strong>Programming Paradigms:</strong></p><table border="1"><tr><th>Acronym</th><th>Full Form</th></tr><tr><td>OOP</td><td>?</td></tr><tr><td>FP</td><td>Functional Programming</td></tr></table>',
                'difficulty' => 'Hard',
                'marks' => 3,
                'status' => 'Published',
                'options' => [
                    ['text' => 'Object-Oriented Programming', 'correct' => true],
                    ['text' => 'Open-Origin Protocol', 'correct' => false],
                    ['text' => 'Operational Optimization Process', 'correct' => false],
                    ['text' => 'Organized Object Procedure', 'correct' => false],
                ]
            ],
        ];

        foreach ($questionsData as $questionData) {
            $category = Category::where('name', $questionData['category'])->first();
            
            $question = Question::create([
                'category_id' => $category->id,
                'question_text' => $questionData['question_text'],
                'difficulty' => $questionData['difficulty'],
                'marks' => $questionData['marks'],
                'status' => $questionData['status'],
            ]);

            foreach ($questionData['options'] as $optionData) {
                $question->options()->create([
                    'option_text' => $optionData['text'],
                    'is_correct' => $optionData['correct'],
                ]);
            }
        }

        $this->command->info('Database seeded successfully with 5 categories and 10 questions!');
    }
}
