<?php

class AreaController extends Framework_BaseController {

    /**
     * 城区数据
     */
    public function areaData() {
        return $data = Area_Area::getInstance()->getAreaData();
    }

    /**
     * 片区数据
     */
    public function zoneData() {
        $areaId = $this->inputDatas['id'];
        return $data = Area_Zone::getInstance()->getZoneList( array( 'area_id' => $areaId ), 0, 100 );
    }

    /**
     * 楼盘数据
     * @return array
     */
    public function villageData() {
        $zoneId = $this->inputDatas['id'];
        $data = array( 'zone_id' => $zoneId );
        return Village_Data::getInstance()->getVillage( $data );
    }

    /**
     * 房间号
     */
    public function roomData() {
        $villageId = $this->inputDatas['village_id'];
        $dong = $this->inputDatas['dong_name'];
        $data = Village_Room::getInstance()->getRoomData( $villageId, $dong );
        $dongData = Village_Dong::getInstance()->getDetail( array( 'village_id' => $villageId, 'name' => $dong ) );
        $data = unserialize( $data['data'] );
        $roomData = array();
        if ( is_array( $data ) )
            foreach ( $data as $k => $room ) {
                $roomData[$room['name']] = $room['name'];
            }
        sort( $roomData );
        return array( 'room' => $roomData, 'layer' => $dongData['layer'] );
    }

    /**
     * 获取小区的详细信息
     * @params village_id 小区id
     * @return array|null
     */
    public function villageDetail() {
        $villageId = $this->inputDatas['village_id'];
        $data = Village_Data::getInstance()->getVillageData( $villageId );
        $dong = Village_Dong::getInstance()->getDongAddress( $villageId );
        if ( $dong )
            $data['dong'] = $dong;
        //            foreach ( $dong as $list ) {
        //                $data['dong'][$list['id']] = $list;
        //                $data['layer'] = $list['layer'];
        //            }
        //        if ( $data['dong'] )
        //            $data['dong'] = Helper_Math::arraySort( $data['dong'], 'name', SORT_ASC, SORT_NUMERIC );
        ////            natsort( $data['dong'] );
        return $data;
    }

    public function getVillageData() {
        $villageId = $this->inputDatas['village_id'];
        $data = Village_Data::getInstance()->getVillageData( $villageId );
        $detail = array(
            'village_id' => $data['village_id'],
            'village_name' => $data['village_name'],
            'finish_date' => $data['finish_date'],
            'manage_fee' => $data['manage_fee'],
            'village_type' => $data['village_type'],
            'area_id' => $data['area_id'],
            'zone_id' => $data['zone_id'],
        );
        if ( $this->inputDatas['special'] ) {
            $detail['lng'] = $data['lng'];
            $detail['lat'] = $data['lat'];
            $detail['polygon'] = $data['polygon'];
        }
        $dong = Village_Dong::getInstance()->getDongAddress( $villageId );
        if ( $dong )
            $detail['dong'] = $dong;
        return $detail;
    }


    public function getZoneDetail() {
        $zoneId = $this->inputDatas['zone_id'];
        $data = Area_Zone::getInstance()->getZoneDetail( $zoneId );
        return array( 'zone' => $data );
    }

    public function updateZone() {
        $zoneId = $this->inputDatas['zone_id'];
        $areaId = $this->inputDatas['zone_id'];
        $data = array( 'lng' => $this->inputDatas['lng'], 'lat' => $this->inputDatas['lat'] );
        Area_Zone::getInstance()->updateZone( $zoneId, $areaId, $data );
        return array( 'state' => 1 );
    }

    /**
     * 获取小区列表
     * @return array
     */
    public function searchVillage() {
        $data = array( 'village_name' => Helper_Tools::replaceSpecialChar( $this->inputDatas['village'] ) );
        if ( empty( $this->inputDatas['village'] ) ) {
            return array();
        }
        $type = $this->inputDatas['type'] ? $this->inputDatas['type'] : 1;
        $limit = $this->inputDatas['limit'] ? $this->inputDatas['limit'] : 10;
        $list = Village_Data::getInstance()->getVillageList( $data, 0, $limit );
        $villageData = array();
        foreach ( $list as $k => $item ) {
            $villageData[$k]['village_id'] = $item['village_id'];
            $villageData[$k]['village_name'] = $item['village_name'];
            $villageData[$k]['village_nickname'] = $item['village_nickname'];
            $villageData[$k]['address'] = $item['village_address'];
            $villageData[$k]['area'] = Area_Area::getInstance()->getAreaDetail( $item['area_id'], 'area_name' );
            $villageData[$k]['zone'] = Area_Zone::getInstance()->getZoneDetail( $item['zone_id'], 'zone_name' );
            $villageData[$k]['num'] = House_Data::getInstance()->getHouseNumByVillageId( $item['village_id'], 1, 1, 1, $type );
        }

        if ( $this->inputDatas['spe'] ) {
            $nicksTotal = array();
            foreach ( $villageData as $list ) {
                if ( empty( $list['village_nickname'] ) ) {
                    continue;
                }
                $nicks = explode( ',', $list['village_nickname'] );
                foreach ( $nicks as $nick ) {
                    $nicksTotal[$nick]['times']++;
                    $nicksTotal[$nick]['count'] += $list['num'];
                    $nicksTotal[$nick]['address'] = $list['address'];
                    $nicksTotal[$nick]['area'] = $list['area'];
                    $nicksTotal[$nick]['zone'] = $list['zone'];
                }
            }
            foreach ( $nicksTotal as $k => $v ) {
                if ( $v['times'] > 1 ) {
                    $push = array(
                        'village_id' => 0,
                        'village_name' => $k,
                        'num' => $v['count'],
                        'address' => $v['address'],
                        'area' => $v['area'],
                        'zone' => $v['zone']
                    );
                    array_push( $villageData, $push );
                }
            }
        }
        $villageData = Helper_Math::arraySort( $villageData, 'village_name' );
        return $villageData;
    }


    /**************后台的数据没有过滤梳理，暂时不和前端公用接口*******************/
    /**
     * 根据zoom和bound来取数据
     * @params zoom <=13只显示区域和数量   14、15显示板块   16、17显示小区   18、19显示栋座
     * @return array
     */
    public function getMapData() {
        $bound = $_POST['bound'];
        $zoom = $_POST['zoom'];
        if ( empty( $bound ) || empty( $zoom ) ) {
            return array();
        }
        $condition = array();
        $condition['status'] = 1;
        $condition['check'] = 1;
        //        $_POST['price'] = '200-250';
        if ( $_POST['price'] || $_POST['area'] || $_POST['room'] ) {
            //            $condition = array(
            //                'price' => $_POST['price'],
            //                'area' => $_POST['area'],
            //                'room' => $_POST['room']
            //            );
            $condition['price'] = $_POST['price'];
            $condition['area'] = $_POST['area'];
            $condition['room'] = $_POST['room'];
        }
        if ( $zoom <= 13 ) {
            $data['areaData'] = Area_Area::getInstance()->getAreaHouseCount( $condition );
        }
        if ( $zoom >= 14 && $zoom <= 15 ) {
            $data['zoneData'] = Area_Zone::getInstance()->getZoneHouseCount( $condition );
        }

        if ( $zoom == 16 || $zoom == 17 ) {
            $bound = explode( ';', $bound );
            $lng = explode( ',', $bound[0] );
            $lat = explode( ',', $bound[1] );
            if ( abs( $lng[1] - $lng[0] ) > 0.3 ) {
                return array();
            }
            $data['villageData'] = Village_Data::getInstance()->getVillageHouseCount( $lng, $lat, $condition );
        }
        if ( $zoom > 17 ) {
            $bound = explode( ';', $bound );
            $lng = explode( ',', $bound[0] );
            $lat = explode( ',', $bound[1] );
            if ( abs( $lng[1] - $lng[0] ) > 0.2 ) {
                return array();
            }
            $data['villageData'] = Village_Data::getInstance()->getVillageHouseCount( $lng, $lat, $condition );
            //            if ( $zoom > 18 )
            $data['houseData'] = Village_Dong::getInstance()->getBandDongData( $lng, $lat, $condition );
        }
        return $data;
    }


    public function getMapHouseList() {
        $limit = 20;
        $page = intval( $this->inputDatas['page'] ) ? intval( $this->inputDatas['page'] ) : 1;
        $start = ( $page - 1 ) * $limit;
        $condition['status'] = 1;
        $condition['check'] = 1;
        if ( empty( $_POST['village_id'] ) ) {
            $bound = $_POST['bound'];
            if ( $_POST['price'] || $_POST['area'] || $_POST['room'] ) {
                $condition['price'] = $_POST['price'];
                $condition['area'] = $_POST['area'];
                $condition['room'] = $_POST['room'];
            }
            $bound = explode( ';', $bound );
            $lng = explode( ',', $bound[0] );
            $lat = explode( ',', $bound[1] );
            $data = Village_Data::getInstance()->getVillageHouseData( $lng, $lat, $condition, $start, $limit );
            $village = Village_Data::getInstance()->getVillageHouseCount( $lng, $lat, $condition );
            $total = 0;
            foreach ( $village as $list ) {
                $total += $list['count'];
            }
        } else {
            if ( $_POST['price'] || $_POST['area'] || $_POST['room'] ) {
                $condition['price'] = $_POST['price'];
                $condition['area'] = $_POST['area'];
                $condition['room'] = $_POST['room'];
                foreach ( array( 'area', 'price', 'room' ) as $item ) {
                    if ( $condition[$item] ) {
                        $areaRange = explode( '-', $condition[$item] );
                        $condition[$item . '_min'] = $areaRange[0];
                        $condition[$item . '_max'] = $areaRange[1];
                    }
                }
            }
            $condition['village_id'] = $_POST['village_id'];
            $data = House_Data::getInstance()->getHouseList( $condition, $start, $limit );
            $total = House_Data::getInstance()->getHouseNum( $condition );
        }
        foreach ( $data as $k => $v ) {
            $data[$k] = House_Data::getInstance()->getHouseData( $v['house_id'] );
            House_Data::exchangeRoom( $data, $k, 0, 0 );
            unset( $data[$k]['contact'] );
            //            unset( $data[$k]['contact'] );
            unset( $data[$k]['image'] );
            unset( $data[$k]['tel'] );
            unset( $data[$k]['linker_man'] );
            unset( $data[$k]['thumbnail_250'] );
        }
        return array( 'data' => $data, 'total' => $total, 'page_num' => ceil( $total / $limit ), 'page' => $page );
    }


}
