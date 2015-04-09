Callbacks and Events
====================

The `PostTypesController` registers multiple callbacks. This is helpfull to make your app flexible.
Plugins are able to do stuff automatically, and you are able to keep your code clean.

All callbacks will be documented in this section.

> Note: When a event-name ends with `.type`, it's mostly the type-name of the current PostType. So, 
`Controller.PostTypes.beforeIndex.type` would be `Controller.PostTypes.beforeIndex.blogs`.

[doc_toc]


PostTypesController::index()
----------------------------

### Controller.PostTypes.beforeIndex.type

This event is fired before the index-action is runned.

### Controller.PostTypes.afterIndex.type

After the action-logic, but before the render this event will be fired. This can be helpful to set
custom variables or overrule them.


PostTypesController::view($id)
------------------------------

### Controller.PostTypes.beforeView.type

This event is fired before the view-action is runned.

### Controller.PostTypes.afterView.type

After the action-logic, but before the render this event will be fired. This can be helpful to set
custom variables or overrule them.


PostTypesController::add()
--------------------------

### Controller.PostTypes.beforeAdd.type

This event is fired before the add-action is runned. Note that you will need this event when you want to customize
the postdata. Using `afterAdd` will be too late ;).

### Controller.PostTypes.afterAdd.type

After the action-logic, but before the render this event will be fired. This can be helpful to set
custom variables or overrule them.


PostTypesController::edit($id)
------------------------------

### Controller.PostTypes.beforeEdit.type

This event is fired before the edit-action is runned. Note that you will need this event when you want to customize
the postdata. Using `afterEdit` will be too late ;).

### Controller.PostTypes.afterEdit.type

After the action-logic, but before the render this event will be fired. This can be helpful to set
custom variables or overrule them.


PostTypesController::delete($id)
--------------------------------

### Controller.PostTypes.beforeDelete.type

This event is fired before the delete-action is runned.

### Controller.PostTypes.afterEdit.type

A delete-action will redirect the user when the item has been deleted. However, when there's something wrong, this event
will be raised. 


