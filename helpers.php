<?php 
/** 
 * Get the base path
 * @param string $path
 * @return string 
*/
function basePath($path = '') {
   if (empty($path)) {
      throw new ValueError("Path cannot be empty.");
  }
  return __DIR__ . '/' . $path;
}
/**
 * Load a view
 * @param string $name
 * @return string
 */
function loadView($name) {
   if (empty($name)) {
       throw new ValueError("View name cannot be empty.");
   }
   $viewPath = basePath("views/{$name}.view.php");
   if (!file_exists($viewPath)) {
       throw new ValueError("View {$name} does not exist.");
   }
   return $viewPath; // Return the path instead of requiring it directly
}

 /**
 * Load a partials
 * @param string $name
 * @return void
 */
function loadPartial($name)
{
   $partialPath = basePath("views/partials/{$name}.php");
   if(file_exists($partialPath))
   {
      require $partialPath;
   }else{
      echo "Partial {$name} does not exist";
   }
}