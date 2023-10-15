Regex for icomoon class to sass variable:
-----------------------------------------

search: \.icon-([a-z0-9-]+):before\s\{\n\scontent:\s("\\e[0-9a-z]+");\n\}
replace: \$icon-$1: $2;

Regex for glyphicon class to sass variable:
-------------------------------------------

search: \.glyphicon-([a-z0-9-]+)\s+\{\s&:before\s\{\scontent:\s("\\[a-z0-9]+");\s\}\s\}
replace: \$icon-$1: $2;
