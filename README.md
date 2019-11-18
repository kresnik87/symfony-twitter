

Installation
============

### Step 2: 
Now use the bash to run installation
The bash require a Proyect alias and repository as arguments
During installation, the bash ask for apply recipes, use "a" option
Next requiere a valid user and password for your local database 
And ask for a optional enviorments values
```console
  cd base
  bash ./init.sh {proyect alias} {github link}
```

Know Issues
============
Eventualy api platform or easyadmin cant loads styles and some assets correctly
Try to reinstall assets
```console
php bin/console assets:install --symlink
´´´
If this doesn't works check public folder permissions and the base url is the real url of your virtualhost

