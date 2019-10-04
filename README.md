We need to develop a very simple module for a Magento 2.2.3 store.
The module will allow customers that we approve to checkout with Purchase Order.
By default, the Purchase order payment method on the site will not be available to general customers.

The module will be installed via composer, owned and maintained by us after the module is successfully created.
You will not be given access to our store, the module must be able to fully function as it's own entity.

1. Admin section

- System/Configuration
-- Module enabled: Yes/No

- Customer grid
-- Add an available column for "Can use PO" : Yes/No
- Individual Customer record
-- Customer info 
--- Is customer allowed to use Purchase order: Yes/No


2. Frontend 

- When available checkout methods are listed, if current customer is approved for Purchase Order, the Purchase Order payment method will also be available.

## Coding

1. You must include tests and pass all the following tests for this module.

- Create customer attribute
- Validate if a customer is allowed to use Purchase Order.
- Validate that the Purchase Order payment method will be available for customers that are allowed to use Purchase Order.
- Pass all API calls below.

2. API

- Allow {custmer_id} to use Purchase Order
- Disallow {custmer_id} to use Purchase Order
- Retrieve list of customers that are allowed use Purchase Order

3. Setup

- A setup script that will add the new customer attribute `can_use_po`

4. Git

- It goes without saying but I'll mention it nevertheless, the module must be built using GIT and clear commit messages.

5. Ownership

- All work done on this module will be owned by us.
