# Shomobay Project Testing Checklist

Project: Shomobay - Anti-Syndicate Neighborhood Bulk Buying  
Course: CSE470 Software Engineering  
Framework: Laravel  
Architecture: MVC  

---

## Demo User Roles

### Neighbor
Used for creating group carts, joining carts, accepting vendor bids, escrow payment, claim token, dispute, rating, and substitution voting.

### Vendor
Used for vendor profile, bidding, revenue analytics, accepted orders, substitution request, seasonality alerts, and route optimization.

### Admin
Used for vendor approval, dispute resolution, and system health tracking.

---

## Sprint 1: Authentication, Roles, Profiles, Admin Approval

### Role-Based Dashboards
- Neighbor sees neighbor dashboard.
- Vendor sees vendor dashboard.
- Admin sees admin dashboard.

### Neighbor Profile
- Neighbor can update apartment building.
- Neighbor can update location coordinates.

### Vendor Profile
- Vendor can update business name.
- Vendor can upload trade license file information.

### Admin Vendor Approval
- Admin can view vendor profiles.
- Admin can approve vendors.
- Admin can mark vendors as pending again.

---

## Sprint 2: Group Cart and Contribution System

### Group Cart Database
- Group carts store creator, item, building, coordinates, target weight, current weight, deadline, and status.

### Group Cart Creation
- Neighbor can create a group cart.
- Target weight must satisfy the grocery item minimum bulk weight.

### Cart Contribution
- Neighbor can join group cart with quantity in kg.
- Neighbor can update own contribution.
- Neighbor can remove own contribution.
- Current cart weight recalculates automatically.

### Dynamic Price Drop
- Current price per kg drops as group cart gets closer to wholesale threshold.
- Price moves from market price toward wholesale price.

### Automated Split Bill
- Each contributor sees estimated bill.
- Total estimated bill is shown.

### Geofenced Cart Filtering
- Neighbor sees carts from same building.
- Neighbor sees carts within 1 km radius.

---

## Sprint 3: Vendor Bidding and Escrow

### Vendor Bidding
- Verified vendor can browse threshold-met group carts.
- Vendor can submit bid price, delivery fee, estimated delivery time, and note.

### Bid Acceptance
- Group cart creator can accept a vendor bid.
- Accepted bid creates an order.
- Other bids become rejected.
- Group cart status becomes ordered.

### Escrow Wallet Hold
- Neighbor wallet balance decreases after bid acceptance.
- Neighbor escrow balance increases.
- Escrow hold transaction is recorded.

### Automated Refund Trigger
- Group cart creator can expire failed active cart.
- Group cart creator can refund escrow-held order.
- Refund returns escrow amount to wallet balance.
- Order status becomes refunded.
- Cart status becomes expired.

### Escrow Release on Delivery
- Group cart creator can mark order as delivered.
- Escrow amount is released to vendor wallet.
- Vendor payment transaction is recorded.
- Order status becomes delivered.

### Vendor Revenue Analytics
- Vendor can view monthly expected earnings.
- Vendor can view total expected earnings.
- Vendor can view active pending bids.
- Vendor can view accepted orders.
- Vendor can view most requested grocery items.

---

## Sprint 4: Delivery, Quality, Admin, and Final Polish

### Digital Claim Tokens
- Claim tokens are generated after bid acceptance.
- Neighbor can view claim token list.
- Neighbor can open token details.
- Neighbor can mark item share as claimed.

### Delivery Coordinator Discount
- Group cart creator can select delivery coordinator.
- Selected neighbor receives 5% discount from escrow.
- Discount amount moves back to wallet balance.
- Order stores coordinator information.

### Produce Quality Rating
- Neighbor can rate delivered order from 1 to 5.
- Neighbor can add comment.
- One neighbor can rate one order once.
- Ratings are shown on group cart details page.
- Average quality score is shown.

### Substitution Voting
- Vendor can propose substitute item for escrow-held order.
- Neighbor contributors can approve or reject.
- One neighbor has one vote but can update vote while pending.
- Majority approval changes status to approved.
- Majority rejection changes status to rejected.

### Dispute Resolution Dashboard
- Neighbor can submit dispute for delivered order.
- Dispute stores reason and requested refund amount.
- Admin can view all disputes.
- Admin can resolve or reject disputes.
- Admin note and resolved time are saved.

### Admin System Health Dashboard
- Admin can view total users.
- Admin can view vendor approval status.
- Admin can view cart and order status.
- Admin can view disputes.
- Admin can view substitution status.
- Admin can view wallet and escrow summary.
- Admin can view recent orders and disputes.

### Seasonality Alerts
- User can view grocery seasonality alerts.
- System shows in-season, season starts soon, or out-of-peak-season status.
- System shows market price, wholesale price, and possible savings percentage.

### Delivery Route Optimization
- Vendor can view escrow-held orders as delivery stops.
- System suggests delivery order based on coordinates.
- Route page shows total stops, buildings, weight, distance, and order value.

---

## Important Demo Flow

1. Login as neighbor.
2. Create group cart.
3. Add wallet balance.
4. Join group cart with enough quantity to reach threshold.
5. Login as verified vendor.
6. Submit vendor bid.
7. Login as neighbor creator.
8. Accept vendor bid.
9. Check escrow hold.
10. Check claim token.
11. Vendor can create substitution request.
12. Neighbor can vote on substitution.
13. Neighbor creator can select delivery coordinator.
14. Neighbor creator can mark order delivered.
15. Neighbor can rate delivered order.
16. Neighbor can submit dispute.
17. Admin can resolve dispute.
18. Admin can view system health.
19. Vendor can view route optimization.
20. User can view seasonality alerts.

---

## Git Naming Convention Used

Commit format:

```text
<type>: <description>