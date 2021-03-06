# Symfony-Exam

CIQUAL importation
====

A famous nutritionist is in need of a search engine dedicated to nutrional data.
As for now, he only has access to the following CIQUAL table : https://pro.anses.fr/tableciqual/Documents/Table_Ciqual_2016.csv

He is asking you to help him build a modern REST API to look into this database and match a string against the value of columns ORIGGPFR
 and ORIGFDNM.

Features
----

- You provide a template with an input which acts like a search bar. Users will enter ingredient names in this search bar
- You will return a list of possible matches against this search string (ingredient names from the CSV file)
- When the user clicks a match, he gets the list of nutrients listed in the csv file.

How to
----

- You should import the CSV file in a MySQL format
- Requests are made through a REST API
- JSON data is sent back
- Do not spend much time on design before you made your best on the backend part


Extra
----

- You are free to impress us with a nice design
- You can add extra features (display nutrient pictures, etc)
- You can optimize the UX search process

Stack
----

Last stable versions of :
- mySQL
- Symfony3
- php7

FAQ
----

### How do I start the project?

You need a clean install of a symfony3 repository.

### What about sort ordering and text filtering?

Try to display the most relevant results first. You're free to opimize the search query stirng and the search process.

### What should I do when I'm finished?

Please create a repository on Github and send us its link.

### How is the exam graded?

We are looking for idiomatic use of php/symfony, and the ability to solve the problems with code that is clean and easy to read. Even though it's very small in scope, please show us how you would use the language and conventions to structure things in a clear and maintainable way.

Think of it as part of an application : the organisation has to be compatible with a larger project. Try to use Symfony best standards.

### This looks like it will take a while and I'm pretty busy

You're right! With something open-ended like this you could easily spend a week polishing and getting it just right. We don't expect you to do this, and we'll do our best to make sure you're not disadvantaged by this.

When we grade this exam we're not giving you a "score out of 100" for how many features you complete. We're trying to get some insight into your process, to see the way you work. So, by all means, spend more time if you want to. But you are also free to leave certain features out and give a written explanation of how you would approach it. The best approach is to spend your time on the features that you think is the best way to show us your strengths and experience.

=================================================
=================================================

Installation
----

0. Install the project with COMPOSER 

> composer install

1. Create the "USER" & "DATABASE" with phpmyadmin

2. Execute the DOCTRINE migrations with this command:

> php bin/console doctrine:migrations:migrate

3. Execute the first script "IMPORT - Ingredient families" (~2min)

> php bin/console --env=prod foodmeup:nutrition:import-ingredient-families

4. Execute the second script "IMPORT - Ingredients" (~9min)

> php bin/console --env=prod foodmeup:nutrition:import-ingredients

5. For access to the data, configure the standard VHOST "SYMFONY_3"

All APi is working, and here is an example of resources created:


========


> /families | "Provides access to all families of ingredients"

> * this link "contents" provide access to translation related for each families

> * this link "ingredients" provide access to ingredients related for each families

> * the filter "search" provide to search on the name of families

========

> /ingredients | "Provides access to all ingredients"

> * this link "contents" provide access to translation related for each ingredients

> * the filter "search" provide to search on the name of ingredients

> * the filter "family_uuid" provide to filter on one family