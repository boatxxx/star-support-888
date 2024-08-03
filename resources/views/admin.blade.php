<!DOCTYPE html>
<html>
<head>
    @section('title', 'Home')
</head>
@extends('layouts.app')
<body>
    @section('content')

    <div class="container">
        <div class="page-inner">
          <div
            class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
          >
            <div>
              <h3 class="fw-bold mb-3">Dashboard</h3>   <h1>ยินดีต้อนรับ, {{ $user->name }}!</h1>
              <h6 class="op-7 mb-2"> บริษัท สตาร์ซัพพอร์ต 999 (ประเทศไทย) จํากัด </h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
              <a href="#" class="btn btn-label-info btn-round me-2">จัดการเมนู</a>
              <a href="#" class="btn btn-primary btn-round">เพิ่ม Order</a>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div
                        class="icon-big text-center icon-primary bubble-shadow-small"
                      >
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">พนักงานทั้งหมด</p>
                        <h4 class="card-title">5 คน </h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div
                        class="icon-big text-center icon-info bubble-shadow-small"
                      >
                        <i class="fas fa-user-check"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">ลูกค้า</p>
                        <h4 class="card-title">1303</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div
                        class="icon-big text-center icon-success bubble-shadow-small"
                      >
                        <i class="fas fa-luggage-cart"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">ยอดขาย</p>
                        <h4 class="card-title">$ 1,345</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div
                        class="icon-big text-center icon-secondary bubble-shadow-small"
                      >
                        <i class="far fa-check-circle"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Order</p>
                        <h4 class="card-title">576</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">ยอดขายทั้งหมด</div>
                    <div class="card-tools">
                      <a
                        href="#"
                        class="btn btn-label-success btn-round btn-sm me-2"
                      >
                        <span class="btn-label">
                          <i class="fa fa-pencil"></i>
                        </span>
                        Export
                      </a>
                      <a href="#" class="btn btn-label-info btn-round btn-sm">
                        <span class="btn-label">
                          <i class="fa fa-print"></i>
                        </span>
                        Print
                      </a>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="chart-container" style="min-height: 375px">
                    <canvas id="statisticsChart"></canvas>
                  </div>
                  <div id="myChartLegend"></div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-primary card-round">
                <div class="card-header">
                  <div class="card-head-row">
                    <div class="card-title">ยอดขายรายวัน</div>
                    <div class="card-tools">
                      <div class="dropdown">
                        <button
                          class="btn btn-sm btn-label-light dropdown-toggle"
                          type="button"
                          id="dropdownMenuButton"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          Export
                        </button>
                        <div
                          class="dropdown-menu"
                          aria-labelledby="dropdownMenuButton"
                        >
                          <a class="dropdown-item" href="#">Action</a>
                          <a class="dropdown-item" href="#">Another action</a>
                          <a class="dropdown-item" href="#"
                            >Something else here</a
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-category">วันที่ 1 มีนาคม 2567</div>
                </div>
                <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                    <h1>$4,578.58</h1>
                  </div>
                  <div class="pull-in">
                    <canvas id="dailySalesChart"></canvas>
                  </div>
                </div>
              </div>
              <div class="card card-round">
                <div class="card-body pb-0">
                  <div class="h1 fw-bold float-end text-primary">+5%</div>
                  <h2 class="mb-2">17</h2>
                  <p class="text-muted">ผู้ใช้งานระบบต่อวัน</p>
                  <div class="pull-in sparkline-fix">
                    <div id="lineChart"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                    <h4 class="card-title">ยอดขายของเซลล์แต่ล่ะคน</h4>
                    <div class="card-tools">
                      <button
                        class="btn btn-icon btn-link btn-primary btn-xs"
                      >
                        <span class="fa fa-angle-down"></span>
                      </button>
                      <button
                        class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"
                      >
                        <span class="fa fa-sync-alt"></span>
                      </button>
                      <button
                        class="btn btn-icon btn-link btn-primary btn-xs"
                      >
                        <span class="fa fa-times"></span>
                      </button>
                    </div>
                  </div>
                  <p class="card-category">
                    ข้อมูลนี้เป็นข้อมูลดิบ ยังไม่รวมการตัดยอด
                  </p>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="table-responsive table-hover table-sales">
                        <table class="table">
                          <tbody>
                            <tr>
                              <td>
                                <div class="flag">
                                  <img
                                    src="{{ asset('assets/img/flags/id.png') }}"
                                    alt="indonesia"
                                  />
                                </div>
                              </td>
                              <td>Indonesia</td>
                              <td class="text-end">2.320</td>
                              <td class="text-end">42.18%</td>
                            </tr>
                            <tr>
                              <td>
                                <div class="flag">
                                  <img
                                    src="{{ asset('assets/img/flags/us.png') }}"
                                    alt="united states"
                                  />
                                </div>
                              </td>
                              <td>USA</td>
                              <td class="text-end">240</td>
                              <td class="text-end">4.36%</td>
                            </tr>
                            <tr>
                              <td>
                                <div class="flag">
                                  <img
                                    src="{{ asset('assets/img/flags/au.png') }}"
                                    alt="australia"
                                  />
                                </div>
                              </td>
                              <td>Australia</td>
                              <td class="text-end">119</td>
                              <td class="text-end">2.16%</td>
                            </tr>
                            <tr>
                              <td>
                                <div class="flag">
                                  <img
                                    src="{{ asset('assets/img/flags/ru.png') }}"
                                    alt="russia"
                                  />
                                </div>
                              </td>
                              <td>Russia</td>
                              <td class="text-end">1.081</td>
                              <td class="text-end">19.65%</td>
                            </tr>
                            <tr>
                              <td>
                                <div class="flag">
                                  <img
                                    src="assets/img/flags/cn.png"
                                    alt="china"
                                  />
                                </div>
                              </td>
                              <td>China</td>
                              <td class="text-end">1.100</td>
                              <td class="text-end">20%</td>
                            </tr>
                            <tr>
                              <td>
                                <div class="flag">
                                  <img
                                    src="assets/img/flags/br.png"
                                    alt="brazil"
                                  />
                                </div>
                              </td>
                              <td>Brasil</td>
                              <td class="text-end">640</td>
                              <td class="text-end">11.63%</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mapcontainer">
                        <div
                          id="world-map"
                          class="w-100"
                          style="height: 300px"
                        ></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

                <div class="card-body p-0">
                  <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center mb-0">
                      <thead class="thead-light">
                        <tr>
                          <th scope="col">รายการขาย</th>
                          <th scope="col" class="text-end">Date & Time</th>
                          <th scope="col" class="text-end">Amount</th>
                          <th scope="col" class="text-end">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">
                            <button
                              class="btn btn-icon btn-round btn-success btn-sm me-2"
                            >
                              <i class="fa fa-check"></i>
                            </button>
                            Payment from #10231
                          </th>
                          <td class="text-end">Mar 19, 2020, 2.45pm</td>
                          <td class="text-end">$250.00</td>
                          <td class="text-end">
                            <span class="badge badge-success">Completed</span>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">
                            <button
                              class="btn btn-icon btn-round btn-success btn-sm me-2"
                            >
                              <i class="fa fa-check"></i>
                            </button>
                            Payment from #10231
                          </th>
                          <td class="text-end">Mar 19, 2020, 2.45pm</td>
                          <td class="text-end">$250.00</td>
                          <td class="text-end">
                            <span class="badge badge-success">Completed</span>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">
                            <button
                              class="btn btn-icon btn-round btn-success btn-sm me-2"
                            >
                              <i class="fa fa-check"></i>
                            </button>
                            Payment from #10231
                          </th>
                          <td class="text-end">Mar 19, 2020, 2.45pm</td>
                          <td class="text-end">$250.00</td>
                          <td class="text-end">
                            <span class="badge badge-success">Completed</span>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">
                            <button
                              class="btn btn-icon btn-round btn-success btn-sm me-2"
                            >
                              <i class="fa fa-check"></i>
                            </button>
                            Payment from #10231
                          </th>
                          <td class="text-end">Mar 19, 2020, 2.45pm</td>
                          <td class="text-end">$250.00</td>
                          <td class="text-end">
                            <span class="badge badge-success">Completed</span>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">
                            <button
                              class="btn btn-icon btn-round btn-success btn-sm me-2"
                            >
                              <i class="fa fa-check"></i>
                            </button>
                            Payment from #10231
                          </th>
                          <td class="text-end">Mar 19, 2020, 2.45pm</td>
                          <td class="text-end">$250.00</td>
                          <td class="text-end">
                            <span class="badge badge-success">Completed</span>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">
                            <button
                              class="btn btn-icon btn-round btn-success btn-sm me-2"
                            >
                              <i class="fa fa-check"></i>
                            </button>
                            Payment from #10231
                          </th>
                          <td class="text-end">Mar 19, 2020, 2.45pm</td>
                          <td class="text-end">$250.00</td>
                          <td class="text-end">
                            <span class="badge badge-success">Completed</span>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">
                            <button
                              class="btn btn-icon btn-round btn-success btn-sm me-2"
                            >
                              <i class="fa fa-check"></i>
                            </button>
                            Payment from #10231
                          </th>
                          <td class="text-end">Mar 19, 2020, 2.45pm</td>
                          <td class="text-end">$250.00</td>
                          <td class="text-end">
                            <span class="badge badge-success">Completed</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



    </div>

    <!-- Custom template | don't include it in your project! -->
    <div class="custom-template">
      <div class="title">Settings</div>
      <div class="custom-content">
        <div class="switcher">
          <div class="switch-block">
            <h4>Logo Header</h4>
            <div class="btnSwitch">
              <button
                type="button"
                class="selected changeLogoHeaderColor"
                data-color="dark"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="blue"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="purple"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="light-blue"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="green"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="orange"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="red"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="white"
              ></button>
              <br />
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="dark2"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="blue2"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="purple2"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="light-blue2"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="green2"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="orange2"
              ></button>
              <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="red2"
              ></button>
            </div>
          </div>
          <div class="switch-block">
            <h4>Navbar Header</h4>
            <div class="btnSwitch">
              <button
                type="button"
                class="changeTopBarColor"
                data-color="dark"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="blue"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="purple"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="light-blue"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="green"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="orange"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="red"
              ></button>
              <button
                type="button"
                class="selected changeTopBarColor"
                data-color="white"
              ></button>
              <br />
              <button
                type="button"
                class="changeTopBarColor"
                data-color="dark2"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="blue2"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="purple2"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="light-blue2"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="green2"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="orange2"
              ></button>
              <button
                type="button"
                class="changeTopBarColor"
                data-color="red2"
              ></button>
            </div>
          </div>
          <div class="switch-block">
            <h4>Sidebar</h4>
            <div class="btnSwitch">
              <button
                type="button"
                class="changeSideBarColor"
                data-color="white"
              ></button>
              <button
                type="button"
                class="selected changeSideBarColor"
                data-color="dark"
              ></button>
              <button
                type="button"
                class="changeSideBarColor"
                data-color="dark2"
              ></button>
            </div>
          </div>
        </div>
      </div>
      <div class="custom-toggle">
        <i class="icon-settings"></i>
      </div>

    </div>
    @endsection

    <!-- End Custom template -->
</body>
</html>