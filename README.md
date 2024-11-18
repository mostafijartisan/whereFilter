# Core Idea of whereableFilter Laravel package

The package allows developers to dynamically apply `where` and `whereLike` filters to Eloquent queries without writing repetitive query-building logic in their controllers.

Instead of explicitly writing `->where()` or `->whereLike()` statements for each field, you define an array of filters in the model, and the package automatically applies those filters based on the request data.

---

## How it works

1. **Model class config**:

-   You have to import Filterable trait in your desire model class.
-   Then you have to create a `$filters` property that defines which fields can be filtered and how (e.g., `where`, `whereLike`).

-   Code Example:

    ```php
    <?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Vendor\WhereableFilter\Traits\Filterable;

    class Product extends Model
    {

        use Filterable;

        protected $filters = [
            ['column' => 'name', 'queryType' => 'whereLike'],
            ['column' => 'size', 'queryType' => 'where'],
            ['column' => 'color', 'queryType' => 'where'],
            ['column' => 'status', 'queryType' => 'where'],
        ];
    }
    ```

2. **Controller class config**:

-   In the controller, you simply pass the request data to the query using the scope:
-   `whereFilter` scope already defined in trait of package and this trait already used in model class. that's why we will can access whereFilter scope from controller.
    ```php
    $query = Model::query();
    $query->whereFilter($request->all());
    ```

### **Advantages**

1. **Reduces Repetition**:

-   No need to write individual `where` or `whereLike` statements for each field.

2. **Scalable**:

-   Adding new filters is as simple as updating the `$filters` array in the model.

3. **Clean Code**:

-   Query-building logic is separated from controllers, making the code easier to read and maintain.

4. **Reusability**:

-   The package works across multiple models without duplicating logic.

---

### **Behind the Scenes**

Here’s a quick breakdown of what's happening at each layer:

| Layer          | Role                                                                                      |
| -------------- | ----------------------------------------------------------------------------------------- |
| **Model**      | Defines the `$filters` array and provides a `scopeWhereFilter` method to use the package. |
| **Package**    | Implements the `whereFilter` method, which applies the filters dynamically.               |
| **Controller** | Simplifies query-building by calling the scope directly.                                  |
| **Request**    | Provides the input data used for filtering the query.                                     |

---
