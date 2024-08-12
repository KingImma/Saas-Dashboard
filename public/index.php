<?php
 
  require "/xamp3/htdocs/Task for dashboard/public/db.php";
  require "check_session.php";

  $stmt = $pdo->prepare('
      SELECT users.id, users.name, users.email, users.created_at, roles.role_name FROM users
      LEFT JOIN user_roles ON users.id = user_roles.user_id
      LEFT JOIN roles ON user_roles.role_id = roles.id
  ');
  $stmt->execute();
  $results = $stmt->fetchAll();

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id = $_POST['user_id'];
    $role_id = $_POST['role_id'];

    $checkUser = $pdo->prepare('SELECT * FROM users WHERE id = :user_id');
    $checkUser->execute(['user_id' => $user_id]);

    if($checkUser ->rowCount() > 0){
      $deleteRole = $pdo->prepare('DELETE FROM user_roles WHERE user_id = :user_id');
      $deleteRole->execute(['user_id' => $user_id]);

      $assignRole = $pdo->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)');
        if($assignRole->execute(['user_id' => $user_id, 'role_id' => $role_id])){
          echo "Role updated successfully";
          header("Location: index.php");
          exit();
        }else{
          echo "Failed to update user role";
        }
    }else{
      echo "User not found";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Saas Dashboard</title>
    <style>
      .sidebar:hover ~ .main-content{
          margin-left: 15rem;
          padding-left: 3%;
          padding-right: 1%;
      }
    </style>
</head>
<body class="bg-[#f3f3f4] mx-auto">
<img src="" class="ndjfjhfgfig" alt="">
    <div class="flex">
        <!-- sidebar -->
        <div class="sidebar w-14 group hover:w-64 transition-all duration-300 z-10 bg-[#101010] h-screen fixed overflow-hidden">
          <div class="flex flex-col justify-between h-full px-5 py-8">
            <div class="mx-auto">
              <!-- title -->
              <div class="text-4xl mb-5 text-center text-[#ffffff] underline underline-offset-8" style="font-family: 'Itim', 'cursive'">
                
              </div>
              <!-- menu items -->
              <div class="mx-auto mt-14">
                <a href="#overview" class="flex items-center gap-2 px-0 hover:px-3 rounded-xl transition-all duration-300 text-gray-200 hover:bg-gray-500 mb-3">
                  <img class="h-[5vh] -ml-2 p-1 " src="images/hash.png" alt="">
                  Dashboard Overview
                </a>
                <a href="#user-management" class="flex items-center gap-2 px-0 hover:px-3 rounded-xl transition-all duration-300 text-gray-200 hover:bg-gray-500 mb-3">
                  <img class="h-[5vh] p-1 -ml-2" src="images/user.png" alt="">
                  User <br>Management
                </a>
                <a href="#role-management" class="flex items-center gap-2 px-0 hover:px-3 rounded-xl transition-all duration-300 text-gray-200 hover:bg-gray-500 mb-3">
                  <img class="h-[5vh] p-1 -ml-2" src="images/tasks.png" alt="">
                  Product <br>Management
                </a>
                <a href="#permission-management" class="flex items-center gap-2 px-0 hover:px-3 rounded-xl transition-all duration-300 text-gray-200 hover:bg-gray-500 mb-3">
                  <img class="h-[5vh] p-1 -ml-2" src="images/permission.png" alt="">
                  Permission <br>Management
                </a>
                <a href="#reports" class="flex items-center gap-2 px-0 hover:px-3 rounded-xl transition-all duration-300 text-gray-200 hover:bg-gray-500 mb-3">
                  <img class="h-[5vh] p-1 -ml-1" src="images/report.png" alt="">
                  Reports
                </a>
                <a href="#notifications" class="flex items-center gap-2 px-0 hover:px-3 rounded-xl transition-all duration-300 text-gray-200 hover:bg-gray-500 mb-3">
                  <img class="h-[5vh] p-1 -ml-2" src="images/notification.png" alt="">
                  Notifications 
                </a>
              </div>
            </div>
            <div class="text-center hidden w-0 group-hover:w-full group-hover:block transition duration-300 text-gray-700 bg-red-100 p-2 rounded-md">
              <div>
                Buy premium for advanced
                <a class="underline cursor-pointer">Features</a>
              </div>
            </div>
          </div>
        </div>


        <!-- main -->
        <div class="main-content w-full transition-all duration-300 bg-white flex-1 ml-[3.5%] flex flex-col space-y-4 h-[150vh] overflow-y-scroll p-5">
          <!-- top bar -->
          <div class="flex justify-between items-ceter">
            <div class="flex items-center gap-2 border-b border-gray-400 pb-1">
              <i class="ri-search-line text-gray-500"></i>
              <input placeholder="Search here..." class="bg-transparent w-80 outline-none border-none"/>
            </div>
            <div class="flex flex-row gap-10">
                <div class="flex flex-row space-x-3 my-2">
                    <img class="h-[3vh] w-[50%]" src="images/mail.png" alt="">
                    <img class="h-[3vh] w-[50%]" src="images/bell.png" alt="">
                </div>
                <a href="logout.php">
                  <button class="bg-black px-10 py-2 rounded-md text-white cursor-pointer">
                    Logout
                  </button>
                </a>
            </div>
          
          </div>
          <!-- content -->
          <div id="overview"  class="p-6 rounded-t-xl bg-[#F1F1F1] h-[150vh]">
            <!-- title -->
            <div class="flex justify-between items-center mt-10 md:mt-10 lg:mt-3 xl:mt-2">
              <div class="flex items-center gap-5">
                <div>
                  <div class="text-2xl md:text-3xl lg:text-4xl xl:text-4xl font-bold mb-1" style="font-family: 'Itim', 'cursive'">
                    Overview
                  </div>
                  <div class="text-gray-500 text-sm">Briefing of all activities</div>
                </div>
              </div>
              <div class="flex items-center">
                <div class="border-r-2 border-gray-300 pr-5">
                  <div class="text-lg md:text-xl lg:text-2xl xl:text-3xl font-bold">168</div>
                  <div class="text-gray-500 text-sm">Products</div>
                </div>
                <div class="pl-5">
                  <div class="text-lg md:text-xl lg:text-2xl xl:text-3xl font-bold">392</div>
                  <div class="text-gray-500 text-sm">Sales</div>
                </div>
              </div>
            </div>
            <!-- cards -->
            <div class="grid grid-cols-5 gap-8 mt-10 md:gap-5">
              <!--First card-->
              <div class="border rounded-lg h-[20vh]  bg-white overflow-hidden shadow-md hover:shadow-xl">
                <div class="p-5 pb-0 flex flex-col ">
                    <div class="text-center self-center flex bg-gray-200 p-2 rounded-full">
                        <img class="h-full md:h-[3vh] lg:h-[3vh] xl:h-[3vh]" src="images/database.png" alt="">
                    </div>
                  <div class="text-[12px] md:text-[14px] lg:text-[16px] xl:text-[18px]  font-bold text-center w-full mt-2">
                    2.3K
                  </div>
                  <div class="text-center text-[10px] md:text-[12px] lg:text-[14px] xl:text-[16px]  w-full bg-red-50 p-1 my-1 mb-1.5">
                    Request
                  </div>
                  <div class="flex items-center text-gray-700 justify-center gap-2 my-2">
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                  </div>
                </div>
              </div>

              <!--Second card-->
              <div class="border rounded-lg h-[20vh] bg-white overflow-hidden shadow-md hover:shadow-xl">
                <div class="p-5 pb-0 flex flex-col">
                    <div class="text-center self-center flex bg-gray-200 p-2 rounded-full">
                        <img class="h-full md:h-[3vh] lg:h-[3vh] xl:h-[3vh]" src="images/add file.png" alt="">
                    </div>
                  <div class="text-[12px] md:text-[14px] lg:text-[16px] xl:text-[18px]  font-bold text-center w-full mt-2">
                    823
                  </div>
                  <div class="text-center text-[10px] md:text-[12px] lg:text-[14px] xl:text-[16px]  w-full bg-red-50 p-1 my-1 mb-1.5">
                    Approved
                  </div>
                  <div class="flex items-center text-gray-700 justify-center gap-2 my-2">
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                  </div>
                </div>
              </div>

              <!--Third card-->
              <div class="border rounded-lg h-[20vh] bg-white overflow-hidden shadow-md hover:shadow-xl">
                <div class="p-5 pb-0 flex flex-col">
                    <div class="text-center self-center flex bg-gray-200 p-2 rounded-full">
                        <img class="h-full md:h-[3vh] lg:h-[3vh] xl:h-[3vh]" src="images/monitor.png" alt="">
                    </div>
                  <div class="text-[12px] md:text-[14px] lg:text-[16px] xl:text-[18px]  font-bold text-center w-full mt-2">
                    1.2K
                  </div>
                  <div class="text-center text-[10px] md:text-[12px] lg:text-[14px] xl:text-[16px]  w-full bg-red-50 p-1 my-1 mb-1.5">
                    Products
                  </div>
                  <div class="flex items-center text-gray-700 justify-center gap-2 my-2">
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                  </div>
                </div>
              </div>

              <!--Fourth card-->
              <div class="border rounded-lg h-[20vh] bg-white overflow-hidden shadow-md hover:shadow-xl">
                <div class="p-5 pb-0 flex flex-col">
                    <div class="text-center self-center flex bg-gray-200 p-2 rounded-full">
                        <img class="h-full md:h-[3vh] lg:h-[3vh] xl:h-[3vh]" src="images/box.png" alt="">
                    </div>
                  <div class="text-[12px] md:text-[14px] lg:text-[16px] xl:text-[18px]  font-bold text-center w-full mt-2">
                    200
                  </div>
                  <div class="text-center text-[10px] md:text-[12px] lg:text-[14px] xl:text-[16px] w-full bg-red-50 p-1 my-1 mb-1.5">
                    Sellers
                  </div>
                  <div class="flex items-center text-gray-700 justify-center gap-2 my-2">
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                  </div>
                </div>
              </div>

              <!--Fifth card-->
              <div class="border rounded-lg h-[20vh] bg-white overflow-hidden shadow-md hover:shadow-xl">
                <div class="p-5 pb-0 flex flex-col">
                    <div class="text-center self-center flex bg-gray-200 p-2 rounded-full">
                        <img class="h-full md:h-[3vh] lg:h-[3vh] xl:h-[3vh]" src="images/enduser.png" alt="">
                    </div>
                  <div class="text-[12px] md:text-[14px] lg:text-[16px] xl:text-[18px]  font-bold text-center w-full mt-2">
                    102
                  </div>
                  <div class="text-center text-[10px] md:text-[12px] lg:text-[14px] xl:text-[16px]  w-full bg-red-50 p-1 my-1 mb-1.5">
                    Customers
                  </div>
                  <div class="flex items-center text-gray-700 justify-center gap-2 my-2">
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                  </div>
                </div>
              </div>


            </div>
            <!--Graphs-->
            <div class="relative flex flex-col w-[40%] mt-5 rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
              <div class="relative mx-4 mt-4 flex flex-col gap-4 overflow-hidden rounded-none bg-transparent bg-clip-border text-gray-700 shadow-none md:flex-row md:items-center">
                <div class="w-[3%] h-[2vh] mt-2 rounded-lg bg-gray-900 p-5 text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    aria-hidden="true"
                    class="h-5 w-5 -mt-3 -ml-[10px]"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"
                    ></path>
                  </svg>
                </div>
                <div>
                  <h6 class="block font-sans text-base font-semibold leading-relaxed tracking-normal text-blue-gray-900 antialiased">
                    Quotation Overview
                  </h6>
                  <p class="block max-w-sm font-sans text-[10px] font-normal leading-normal text-gray-700 antialiased">
                    Visualize your data in a simple way 
                  </p>
                </div>
              </div>
              <div class="pt-3 px-2 pb-0">
                <div id="bar-chart"></div>
              </div>
            </div>

          </div>

           <!-- User Management Section -->
           <div id="user-management" class="hidden space-y-10">
            <h2 class="text-2xl font-bold">User Management</h2>
            <!--Members Column-->
            <div>
              <div class="relative flex flex-row justify-between bg-[#f2f3f3] p-5 rounded-t-md">
                <div class="flex flex-col space-y-2">
                  <h1 class="font-bold text-lg">Members List</h1>
                  <p class="text-sm text-gray-500">See information about all members</p>
                </div>
                <div class="flex flex-row gap-2 h-[6vh] place-self-center">
                  <button class="text-black bg-transparent px-4 rounded-lg cursor-pointer border border-black">
                    View all
                  </button>
                  <button id="openModalButton" class="bg-black px-4 py-2 rounded-lg text-white cursor-pointer">
                    Add Member
                  </button>
                </div>
              </div>
              <!--Modal-->
              <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
              <div class="bg-white p-6 rounded-lg shadow-lg w-[50%]">
                <div class="flex flex-row justify-between">
                  <h2 class="text-2xl font-bold mb-4">Add New User</h2>
                  <button id="closeModalButton"><img class="h-[3vh] -mt-4" src="images/close.png" alt=""></button>
                </div>
                <form action="./admin_role/add_user.php" enctype="multipart/form-data" class="border border-gray-400 w-full p-10 rounded-lg" method="post">
                      <div class="flex flex-col space-y-2 mb-5">
                          <label for="name" class="text-black text-sm font-semibold">
                            Name
                          </label>
                          <input type="text" id="name" placeholder="Enter new name" name="name" id="name" class="border border-gray-20 p-2 rounded-xl">
                      </div>
                      <div class="flex flex-col space-y-2 mb-5">
                          <label for="name" class="text-black text-sm font-semibold">
                            Email
                          </label>
                          <input type="email" id="email" placeholder="Enter new email" name="email" id="email" class="border border-gray-20 p-2 rounded-xl">
                      </div>
                      <div class="flex flex-col space-y-2 mb-5">
                        <label class="block text-grey-700 text-sm font-semibold mb-2" for="role">
                          Role
                        </label>
                        <select name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="1">Admin</option>
                            <option value="2">Seller</option>
                            <option value="3">User</option>
                        </select>
                      </div>
                      <p class="text-[12px] text-gray-400 text-center">All passwords are created with a default password</p>
                      <button class="bg-black text-white p-2 w-full mt-5" type="submit">
                        Submit
                      </button>
                </form>
              </div>
            </div>

              <!--Table of users-->
              <div>
                <table class="w-full mt-4 text-left table-auto min-w-max">
                    <thead>
                      <tr>
                        <th class="p-4 border-y border-blue-gray-100 bg-[#eff0f1] bg-opacity-[50.0%]">
                          Name
                        </th>
                        <th class="p-4 border-y border-blue-gray-100 bg-[#eff0f1] bg-opacity-[50.0%]">
                          Email
                        </th>
                        <th class="p-4 border-y border-blue-gray-100 bg-[#eff0f1] bg-opacity-[50.0%]">
                          Role
                        </th>
                        <th class="p-4 border-y border-blue-gray-100 bg-[#eff0f1] bg-opacity-[50.0%]">
                          Created at
                        </th>
                        <th class="p-4 border-y border-blue-gray-100 bg-[#eff0f1] bg-opacity-[50.0%]">
                          Actions
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($results as $result): ?>
                      <tr>
                        <td class="p-4 border-b border-blue-gray-50">
                          <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                              <?= htmlspecialchars($result['name']) ?>
                          </p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                          <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                             <?= htmlspecialchars($result['email']) ?>
                          </p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                          <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                             <?= htmlspecialchars($result['role_name']) ?>
                          </p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                          <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                              <?= htmlspecialchars($result['created_at']) ?>
                          </p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                        <button class="openRoleModalButton bg-gray-800 text-white text-[11px] font-semibold px-2 py-2 rounded-lg cursor-pointer" data-user-id="<?= htmlspecialchars($result['id']) ?>">
                          Manage Roles
                        </button>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                </table>

                <div id="roleModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                  <div class="bg-white p-6 rounded-lg shadow-lg w-[40%]">
                    <div class="flex flex-row justify-between">
                      <h2 class="text-2xl font-bold mb-4">Manage User Role</h2>
                      <button id="closeRoleModalButton"><img class="h-[3vh] -mt-4" src="images/close.png" alt=""></button>
                    </div>
                    <form id="roleForm" action="index.php" method="post" class="border border-gray-400 w-full p-4 rounded-lg">
                      <input type="hidden" name="user_id" id="modalUserId" value="">
                      <div class="flex flex-col space-y-2 mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="role">
                          Role
                        </label>
                        <select name="role_id" id="modalRoleSelect" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                          <option value="1">Admin</option>
                          <option value="2">Seller</option>
                          <option value="3">User</option>
                        </select>
                      </div>
                      <button class="bg-black text-white p-2 w-full mt-5" type="submit">
                        Update Role
                      </button>
                    </form>
                  </div>
                </div>

              </div>
            </div>
        </div>
  
        <!-- Role Management Section -->
        <div id="role-management" class="hidden">
            <h2 class="text-2xl font-bold">Product Management</h2>
            <p>Content for Product Management here.</p>
        </div>
  
        <!-- Permission Management Section -->
        <div id="permission-management" class="hidden">
            <h2 class="text-2xl font-bold">Permission Management</h2>
            <p>Content for Permission Management here.</p>
        </div>
  
        <!-- Reports Section -->
        <div id="reports" class="hidden">
            <h2 class="text-2xl font-bold">Reports</h2>
            <p>Content for Reports here.</p>
        </div>
  
        <!-- Notifications Section -->
        <div id="notifications" class="hidden">
            <h2 class="text-2xl font-bold">Notifications</h2>
            <p>Content for Notifications here.</p>
        </div>
          
        </div>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
      <script>
      const chartConfig = {
        series: [
          {
            name: "Sales",
            data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
          },
        ],
        chart: {
          type: "bar",
          height: 240,
          toolbar: {
            show: false,
          },
        },
        title: {
          show: "",
        },
        dataLabels: {
          enabled: false,
        },
        colors: ["#020617"],
        plotOptions: {
          bar: {
            columnWidth: "40%",
            borderRadius: 2,
          },
        },
        xaxis: {
          axisTicks: {
            show: false,
          },
          axisBorder: {
            show: false,
          },
          labels: {
            style: {
              colors: "#616161",
              fontSize: "12px",
              fontFamily: "inherit",
              fontWeight: 400,
            },
          },
          categories: [
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
        },
        yaxis: {
          labels: {
            style: {
              colors: "#616161",
              fontSize: "12px",
              fontFamily: "inherit",
              fontWeight: 400,
            },
          },
        },
        grid: {
          show: true,
          borderColor: "#dddddd",
          strokeDashArray: 5,
          xaxis: {
            lines: {
              show: true,
            },
          },
          padding: {
            top: 5,
            right: 20,
          },
        },
        fill: {
          opacity: 0.8,
        },
        tooltip: {
          theme: "dark",
        },
      };
      
      const chart = new ApexCharts(document.querySelector("#bar-chart"), chartConfig);
      
      chart.render();

      // Get the modal, close button, and modal form elements
      const roleModal = document.getElementById('roleModal');
      const closeRoleModalButton = document.getElementById('closeRoleModalButton');
      const modalUserId = document.getElementById('modalUserId');

      // Open the modal when clicking a button with the class 'openRoleModalButton'
      document.addEventListener('click', (event) => {
        if (event.target.classList.contains('openRoleModalButton')) {
          const userId = event.target.getAttribute('data-user-id');
          if (userId) {
            modalUserId.value = userId;
            roleModal.classList.remove('hidden');
          }
        }
      });

      // Close the modal when clicking the close button
      closeRoleModalButton.addEventListener('click', () => {
        roleModal.classList.add('hidden');
      });


      </script>
      <script src="index.js"></script>
</body>
</html>