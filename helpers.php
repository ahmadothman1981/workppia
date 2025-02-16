<?php 
/** 
 * Get the base path
 * @param string $path
 * @return string 
*/
function basePath($path = '') 
{
   if (empty($path)) {
      throw new ValueError("Path cannot be empty.");
  }
  return __DIR__ . '/' . $path;
}
/**
 * Load a view
 * @param string $name
 * @return void
 */
function loadView($name , $data = []) {
   if (empty($name)) {
       throw new ValueError("View name cannot be empty.");
   }
   $viewPath = basePath("App/views/{$name}.view.php");
   if (!file_exists($viewPath)) {
       throw new ValueError("View {$name} does not exist.");
   }

   extract($data);
   require $viewPath; // Return the path instead of requiring it directly
}

 /**
 * Load a partials
 * @param string $name
 * @return void
 */
function loadPartial($name , $data = [])
{
   $partialPath = basePath("App/views/partials/{$name}.php");
   if(file_exists($partialPath))
   {
      extract($data);
      require $partialPath;
   }else{
      echo "Partial {$name} does not exist";
   }

  
}
 /**
    * Inspect value
    * @param mixed $value
    * @return void
    */
    function inspect($value)
    {
       echo "<pre>";
       var_dump($value);
       echo "</pre>";
    }
    /**Inspect value and die
     * @param mixed $value
     * @return void
     */
    function inspectAndDie($value)
    {
      echo "<pre>";
      die(var_dump($value));
      
    }

    /**
     * Format salary
     * @param string  $salary
     * @return string Formatted salary
     */
    function formatSalary($salary)
    {
      return '$'. number_format($salary);
    }
    /**
     * Sanitize Data 
     * @param string $data
     * @return string
     */
    function sanitize($data)
    {
      return filter_var(trim($data), FILTER_SANITIZE_SPECIAL_CHARS);
    }
    /**
     * Redirect to a url
     * @param string $url
     * @return void
     */
    function redirect($url)
    {
      header("Location: {$url}");
      exit;
    }